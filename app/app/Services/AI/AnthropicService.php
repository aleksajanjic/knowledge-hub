<?php

namespace App\Services\AI;

use App\Contracts\AIServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnthropicService implements AIServiceInterface
{
    private string $apiKey;
    private string $model;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.anthropic.api_key', '');
        $this->model = config('services.anthropic.model', 'claude-sonnet-4-20250514');
        $this->baseUrl = 'https://api.anthropic.com/v1';
    }

    public function generateAnswer(string $question, ?string $context = null): array
    {
        $prompt = $this->buildPrompt($question, $context);

        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01',
                'Content-Type' => 'application/json',
            ])->timeout(30)->post("{$this->baseUrl}/messages", [
                'model' => $this->model,
                'max_tokens' => 1000,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'system' => 'You are a helpful assistant in a knowledge hub. Provide clear, accurate, and concise answers to technical questions.'
            ]);

            if ($response->failed()) {
                throw new \Exception('Anthropic API request failed: ' . $response->body());
            }

            $data = $response->json();

            return [
                'answer' => $data['content'][0]['text'] ?? 'No answer generated',
                'tokens_used' => ($data['usage']['input_tokens'] ?? 0) + ($data['usage']['output_tokens'] ?? 0),
                'model' => $data['model'] ?? $this->model,
            ];
        } catch (\Exception $e) {
            Log::error('Anthropic Service Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function isAvailable(): bool
    {
        return !empty($this->apiKey);
    }

    public function getProviderName(): string
    {
        return 'Anthropic';
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
