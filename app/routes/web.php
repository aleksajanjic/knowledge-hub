<?php

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
    Route::post('/questions/{question}/upvote', [VoteController::class, 'upvote'])->name('questions.upvote');
    Route::post('/questions/{question}/downvote', [VoteController::class, 'downvote'])->name('questions.downvote');
});

require __DIR__ . '/auth.php';
