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

    public function generateAnswerForQuestion(Question $question, ?int $userId = null): ?Answer
    {
        if (!config('services.ai.enabled', false)) {
            Log::info("AI auto answer is disabled in config.");
            return null;
        }

        if (!$this->aiManager->isAnyProviderAvailable()) {
            Log::warning("No AI providers are available.");
            $this->logFailedRequest($question, 'No AI providers configured');
            return null;
        }

        $userId = $userId ?? $question->user_id;

        $startTime = microtime(true);
        $context = $this->buildContext($question);

        try {
            $questionText = $question->title . "\n\n" . ($question->content ?? $question->title);
            $result = $this->aiManager->generateAnswer($questionText, $context);

            $responseTime = (int) ((microtime(true) - $startTime) * 1000);
            $provider = $this->aiManager->getPrimaryProvider();

            $answer = $question->answers()->create([
                'body' => $result['answer'],
                'user_id' => $userId,
                'votes' => 0,
            ]);

            $this->logSuccessfulRequest(
                $question,
                $provider->getProviderName(),
                $result['model'],
                $questionText,
                $result['answer'],
                $result['tokens_used'],
                $responseTime,
                $userId
            );

            return $answer;
        } catch (Exception $e) {
            $responseTime = (int) ((microtime(true) - $startTime) * 1000);

            Log::error('Failed to generate AI answer: ' . $e->getMessage());

            $this->logFailedRequest(
                $question,
                $e->getMessage(),
                $responseTime,
                $userId
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
        int $responseTime,
        ?int $userId = null
    ): void {
        AIRequestAudit::create([
            'provider' => strtolower($provider),
            'model' => $model,
            'prompt' => $prompt,
            'response' => $response,
            'tokens_used' => $tokensUsed,
            'status' => 'success',
            'question_id' => $question->id,
            'user_id' => $userId ?? $question->user_id,
            'response_time_ms' => $responseTime,
        ]);
    }

    private function logFailedRequest(
        Question $question,
        string $errorMessage,
        ?int $responseTime = null,
        ?int $userId = null
    ): void {
        AIRequestAudit::create([
            'provider' => 'unknown',
            'model' => 'unknown',
            'prompt' => $question->title . "\n\n" . ($question->content ?? $question->title),
            'status' => 'failed',
            'error_message' => $errorMessage,
            'question_id' => $question->id,
            'user_id' => $userId ?? $question->user_id,
            'response_time_ms' => $responseTime,
        ]);
    }

    public function isEnabled(): bool
    {
        return config('services.ai.enabled', false);
    }
}
