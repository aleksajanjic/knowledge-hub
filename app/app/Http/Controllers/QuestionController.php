<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;

class QuestionController extends Controller
{
    /**
     * Display a listing of questions.
     *
     * @return View
     */
    public function index(): View
    {
        $questions = Question::with('user')->latest()->get();

        return view('dashboard', compact('questions'));
    }

    /**
     * Store a newly created question in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Question::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('home')->with('success', 'Question created successfully.');
    }
}
