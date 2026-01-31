<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Question;
use App\Models\Tag;
use App\Services\QuestionFilter;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;

class QuestionController extends Controller
{
    public function index(Request $request): View
    {
        $questionFilter = new QuestionFilter($request);
        $questions = $questionFilter->apply()->withCount('answers')->paginate(10)->withQueryString();

        $allTags = Tag::withCount('questions')
            ->orderBy('questions_count', 'desc')
            ->take(20)
            ->get();

        $categories = Category::with('recursiveChildren')->roots()->get();

        return view('home', compact('questions', 'allTags', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $question = Question::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'user_id' => auth()->id(),
        ]);

        if (!empty($validated['tags'])) {
            $this->attachTags($question, $validated['tags']);
        }

        return redirect()->route('home')->with('success', 'Question created successfully.');
    }

    public function show(Question $question): View
    {
        $question->load(['user', 'tags', 'votes', 'answers.user', 'answers.votes']);

        return view('components.questions.question-detail-content', compact('question'));
    }

    public function create()
    {
        $categories = Category::with('recursiveChildren')->roots()->get();
        return view('questions.create', compact('categories'));
    }

    public function edit(Question $question)
    {
        $question->load('tags');

        return view('components.questions.question-edit-modal', compact('question'));
    }

    public function update(Request $request, Question $question): RedirectResponse
    {
        $validated = $request->validate([
            'title' => "required|string|max:255",
            'content' => "required|string",
            "tags" => "nullable|string",
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $question->update([
            "title" => $validated['title'],
            "content" => $validated['content'],
        ]);

        $question->tags()->detach();

        if (!empty($validated['tags'])) {
            $this->attachTags($question, $validated['tags']);
        }

        return redirect()->route('home')->with("success", 'Question updated successfull.');
    }

    public function destroy(Question $question): RedirectResponse
    {
        $question->delete();

        return redirect()->route('home')->with('success', 'Question deleted successfully.');
    }

    private function attachTags(Question $question, string $tags): void
    {
        $tagNames = array_map('trim', explode(',', $tags));

        foreach ($tagNames as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $question->tags()->attach($tag->id);
        }
    }
}
