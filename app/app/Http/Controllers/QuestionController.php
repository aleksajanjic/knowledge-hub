<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Tag;
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
    public function index(Request $request): View
    {
        $perPage = 5;
        $query = Question::with(['user', 'tags', 'votes']);

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('content', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->has('tag') && $request->tag != '') {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', $request->tag);
            });
        }

        $questions = $query->latest()->paginate($perPage);

        return view('home', compact('questions'));
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
            'tags' => 'nullable|string',
        ]);

        $question = Question::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'user_id' => auth()->id(),
        ]);

        // Handle tags
        if (!empty($validated['tags'])) {
            $tagNames = array_map('trim', explode(',', $validated['tags']));
            foreach ($tagNames as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $question->tags()->attach($tag->id);
            }
        }

        return redirect()->route('home')->with('success', 'Question created successfully.');
    }

    public function show(Question $question)
    {
        $question->load(['user', 'tags', 'votes', 'answers.user', 'answers.votes']);
        return view("components.questions.question-detail-content", compact('question'));
    }
}
