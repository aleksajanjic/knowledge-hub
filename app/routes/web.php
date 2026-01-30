<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
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
    // Question
    Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    Route::get('/questions/{question}/details', [QuestionController::class, 'show'])->name('questions.show');

    // Vote
    Route::post('/questions/{question}/upvote', [VoteController::class, 'upvote'])->name('questions.upvote');
    Route::post('/questions/{question}/downvote', [VoteController::class, 'downvote'])->name('questions.downvote');

    // Answer
    Route::post('/answers/{answer}/upvote', [AnswerController::class, 'upvote'])->name('answers.upvote');
    Route::post('/answers/{answer}/downvote', [AnswerController::class, 'downvote'])->name('answers.downvote');
    Route::post('/questions/{question}/answers', [AnswerController::class, 'store'])->name('answers.store');
    Route::post('/questions/{question}/accept-answer/{answer}', [AnswerController::class, 'acceptAnswer'])
        ->name('answers.accept');
    Route::delete('/answers/{answer}', [AnswerController::class, 'destroy'])
        ->name('answers.destroy');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // User management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::resource('users', UserController::class)->except(['show']);

    // Tag management
    Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
    Route::resource('tags', TagController::class)->except(['show']);
});

require __DIR__ . '/auth.php';
