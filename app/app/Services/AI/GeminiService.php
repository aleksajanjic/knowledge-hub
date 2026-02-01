<?php

namespace App\Services\AI;

use App\Contracts\AIServiceInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Exception;

class GeminiService implements AIServiceInterface
{
    private string $apiKey;
    private string $model;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key', '');
        $this->model = config('services.gemini.model', 'gemini-1.5-flash-preview');
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta';
    }

    public function generateAnswer(string $question, ?string $context = null): array
    {
        $prompt = $this->buildPrompt($question, $context);
        $fullPrompt = "You are a helpful assistant in a knowledge hub. Provide clear, accurate, and concise answers to technical questions.\n\n" . $prompt;

        try {
            $response = Http::timeout(30)->post(
                "{$this->baseUrl}/models/{$this->model}:generateContent?key={$this->apiKey}",
                [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $fullPrompt
                                ]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 1000,
                    ],
                ]
            );

            if ($response->failed()) {
                throw new \Exception('Gemini API request failed: ' . $response->body());
            }

            $data = $response->json();
            $tokensUsed = $data['usageMetadata']['totalTokenCount'] ?? 0;

            return [
                'answer' => $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No answer generated',
                'tokens_used' => $tokensUsed,
                'model' => $this->model,
            ];
        } catch (\Exception $e) {
            Log::error('Gemini Service Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function isAvailable(): bool
    {
        return !empty($this->apiKey);
    }

    public function getProviderName(): string
    {
        return 'Google Gemini';
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
