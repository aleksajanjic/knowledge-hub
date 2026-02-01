<?php

namespace App\Services\AI;

use App\Contracts\AIServiceInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class AIManager
{
    private ?AIServiceInterface $primaryProvider = null;
    private array $providers = [];
    private array $fallbackProviders = [];

    public function __construct()
    {
        $this->initializeProviders();
    }

    private function initializeProviders(): void
    {
        $this->providers = [
            'openai' => app(OpenAIService::class),
            'anthropic' => app(AnthropicService::class),
            'gemini' => app(GeminiService::class)
        ];

        $primaryProviderName = config('services.ai.primary_provider', 'openai');
        $this->primaryProvider = $this->providers[$primaryProviderName] ?? null;

        $fallbackOrder = config('services.ai.fallback_order', ['anthropic', 'gemini', 'openai']);
        foreach ($fallbackOrder as $providerName) {
            if (isset($this->providers[$providerName]) && $providerName !== $primaryProviderName) {
                $this->fallbackProviders[] = $this->providers[$providerName];
            }
        }
    }

    public function generateAnswer(string $question, ?string $context = null): array
    {
        if ($this->primaryProvider && $this->primaryProvider->isAvailable()) {
            try {
                return $this->primaryProvider->generateAnswer($question, $context);
            } catch (Exception $e) {
                Log::warning("Primary AI provider ({$this->primaryProvider->getProviderName()}) failed: {$e->getMessage()}");
            }
        }

        foreach ($this->fallbackProviders as $provider) {
            if ($provider->isAvailable()) {
                try {
                    Log::info("Using fallback AI provider: {$provider->getProviderName()}");
                    return $provider->generateAnswer($question, $context);
                } catch (Exception $e) {
                    Log::warning("Fallback AI provider ({$provider->getProviderName()}) failed: {$e->getMessage()}");
                    continue;
                }
            }
        }

        throw new \Exception('No AI providers are available at this time.');
    }

    public function getPrimaryProvider(): ?AIServiceInterface
    {
        return $this->primaryProvider;
    }

    public function isAnyProviderAvailable(): bool
    {
        if ($this->primaryProvider && $this->primaryProvider->isAvailable()) {
            return true;
        }

        foreach ($this->fallbackProviders as $provider) {
            if ($provider->isAvailable()) {
                return true;
            }
        }

        return false;
    }

    public function getAvailableProviders(): array
    {
        return array_filter($this->providers, fn($provider) => $provider->isAvailable());
    }
}
