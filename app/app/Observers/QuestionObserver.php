<?php

namespace App\Observers;

use App\Models\Question;
use App\Services\AutoAnswerService;
use Exception;
use Illuminate\Support\Facades\Log;

class QuestionObserver
{
    private AutoAnswerService $autoAnswerService;

    public function __construct(AutoAnswerService $autoAnswerService)
    {
        $this->autoAnswerService = $autoAnswerService;
    }

    public function created(Question $question): void
    {
        if (!$this->autoAnswerService->isEnabled()) {
            return;
        }
        try {
            $this->autoAnswerService->generateAnswerForQuestion($question);
        } catch (Exception $e) {
            Log::error("Failed to generate AI answer for question {$question->id}: {$e->getMessage()}");
        }
    }
}
