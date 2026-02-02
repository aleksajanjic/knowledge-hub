<?php

namespace App\Providers;

use App\Contracts\AIServiceInterface;
use App\Models\Question;
use App\Observers\QuestionObserver;
use App\Services\AI\AIManager;
use App\Services\AI\AnthropicService;
use App\Services\AI\GeminiService;
use App\Services\AI\OpenAIService;
use App\Services\AI\OpenRouterService;
use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register AI service implementations
        $this->app->singleton(OpenAIService::class);
        $this->app->singleton(AnthropicService::class);
        $this->app->singleton(GeminiService::class);
        $this->app->singleton(OpenRouterService::class);

        // Register AI Manager
        $this->app->singleton(AIManager::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register question observer for auto-answering
        Question::observe(QuestionObserver::class);
    }
}
