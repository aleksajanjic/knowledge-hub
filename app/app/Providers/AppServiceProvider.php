<?php

namespace App\Providers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            'Question' => Question::class,
            'Answer' => Answer::class,
        ]);
    }
}

