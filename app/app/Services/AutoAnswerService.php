<?php

namespace App\Services;

use App\Models\AIRequestAudit;
use App\Models\Answer;
use App\Models\Question;
use App\Services\AI\AIManager;
use Exception;
use Illuminate\Support\Facades\Log;

class AutoAnswerService
{
    private AIManager $aiManager;

    public function __construct(AIManager $aiManager)
    {
        $this->aiManager = $aiManager;
    }

    public function generateAnswerForQuestion(Question $question): ?Answer
    {
        if (!config('services.ai.enabled', false)) {
            Log::info("AI auto answer is dissabled in config.");
            return null;
        }

        if (!$this->aiManager->isAnyProviderAvailable()) {
            Log::warning("No AI providers are available.");
            $this->logFailedRequest($question, 'No AI providers configured');
            return null;
        }

        $startTime = microtime(true);
        $context = $this->buildContext($question);

        try {
            $result = $this->aiManager->generateAnswer($question->title . "\n\n" . $question->body, $context);

            $responseTime = (int) ((microtime(true) - $startTime) + 1000);
            $provider = $this->aiManager->getPrimaryProvider();

            $answer = Answer::create([
                'question_id' => $question->id,
                'user_id' => null,
                'body' => $result['answer'],
                'is_ai_generated' => true
            ]);

            $this->logSuccessfulRequest(
                $question,
                $provider->getProviderName(),
                $result['model'],
                $question->title . "\n\n" . $question->body,
                $result['answer'],
                $result['tokens_used'],
                $responseTime
            );

            return $answer;
        } catch (Exception $e) {
            $responseTime = (int) ((microtime(true) - $startTime) * 1000);

            Log::error('Failed to generate AI answer: ' . $e->getMessage());

            $this->logFailedRequest(
                $question,
                $e->getMessage(),
                $responseTime
            );

            return null;
        }
    }

    private function buildContext(Question $question): string
    {
        $context = [];

        if ($question->category) {
            $context[] = "Category: {$question->category->name}";

            if ($question->tags && $question->tags->count() > 0) {
                $tagNames = $question->tags->pluck('name')->join(', ');
                $context[] = "Tags: {$tagNames}";
            }
        }

        return implode("\n", $context);
    }

    private function logSuccessfulRequest(
        Question $question,
        string $provider,
        string $model,
        string $prompt,
        string $response,
        int $tokensUsed,
        int $responseTime
    ): void {
        AIRequestAudit::create([
            'provider' => strtolower($provider),
            'model' => $model,
            'prompt' => $prompt,
            'response' => $response,
            'tokens_used' => $tokensUsed,
            'status' => 'success',
            'question_id' => $question->id,
            'user_id' => $question->user_id,
            'response_time_ms' => $responseTime,
        ]);
    }

    private function logFailedRequest(
        Question $question,
        string $errorMessage,
        ?int $responseTime = null
    ): void {
        AIRequestAudit::create([
            'provider' => 'unknown',
            'model' => 'unknown',
            'prompt' => $question->title . "\n\n" . $question->body,
            'status' => 'failed',
            'error_message' => $errorMessage,
            'question_id' => $question->id,
            'user_id' => $question->user_id,
            'response_time_ms' => $responseTime,
        ]);
    }

    public function isEnabled(): bool
    {
        return config('services.ai.enabled', false) && config('services.ai.auto_answer', false);
    }
}
