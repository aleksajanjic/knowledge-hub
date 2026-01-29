<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

// Home/Dashboard route
Route::get('/', [QuestionController::class, 'index'])->middleware(['auth'])->name('home');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Question routes
Route::middleware(['auth'])->group(function () {
    Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('/questions/{question}/details', [QuestionController::class, 'show'])->name('questions.show');

    Route::post('/questions/{question}/upvote', [VoteController::class, 'upvote'])->name('questions.upvote');
    Route::post('/questions/{question}/downvote', [VoteController::class, 'downvote'])->name('questions.downvote');

    // Route::post('/answers/{answer}/upvote', [AnswerController::class, 'upvote'])->name('answers.upvote');
    // Route::post('/answers/{answer}/downvote', [AnswerController::class, 'downvote'])->name('answers.downvote');

    Route::post('/questions/{question}/answers', [AnswerController::class, 'store'])->name('answers.store');
});

require __DIR__ . '/auth.php';
