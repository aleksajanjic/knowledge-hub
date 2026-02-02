<?php

namespace App\Services\AI;

use App\Contracts\AIServiceInterface;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterService implements AIServiceInterface
{
    private string $apiKey;
    private string $model;
    private string $baseUrl = 'https://openrouter.ai/api/v1';

    public function __construct()
    {
        $this->apiKey = config('services.openrouter.api_key', '');
        $this->model = config('services.openrouter.model', 'openai/gpt-4o-mini');
    }

    public function generateAnswer(string $question, ?string $context = null): array
    {
        $prompt = $this->buildPrompt($question, $context);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'HTTP-Referer' => config('app.url', 'https://localhost'),
            ])->timeout(30)->post("{$this->baseUrl}/chat/completions", [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful assistant in a knowledge hub. Provide clear, accurate, and concise answers to technical questions.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => 0.7,
                'max_tokens' => 1000,
            ]);

            if ($response->failed()) {
                throw new Exception('OpenRouter API request failed: ' . $response->body());
            }

            $data = $response->json();

            return [
                'answer' => $data['choices'][0]['message']['content'] ?? 'No answer generated',
                'tokens_used' => $data['usage']['total_tokens'] ?? 0,
                'model' => $data['model'] ?? $this->model,
            ];
        } catch (Exception $e) {
            Log::error('OpenRouter Service Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function isAvailable(): bool
    {
        return !empty($this->apiKey);
    }

    public function getProviderName(): string
    {
        return 'OpenRouter';
    }

    private function buildPrompt(string $question, ?string $context): string
    {
        $prompt = "Question: {$question}";

        if ($context) {
            $prompt = "Context: {$context}\n\n{$prompt}";
        }

        return $prompt;
    }
}
