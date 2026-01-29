<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function store(Request $request, Question $question): string
    {
        $validated = $request->validate([
            'content' => 'required|string|min:10'
        ]);

        $answer = $question->answers()->create([
            'body' => $validated['content'],
            'user_id' => auth()->id()
        ]);

        $answer->load('user');
        return view('components.questions.answer-item', compact('answer', 'question'))->render();
    }

    public function upvote(Answer $answer): JsonResponse
    {
        $answer->upvote(auth()->id());

        return response()->json([
            'votes' => $answer->fresh()->votes,
            'userVote' => $answer->userVote(auth()->id())?->vote
        ]);
    }

    public function downvote(Answer $answer): JsonResponse
    {
        $answer->downvote(auth()->id());

        return response()->json([
            'votes' => $answer->fresh()->votes,
            'userVote' => $answer->userVote(auth()->id())?->vote
        ]);
    }

    public function accept(Question $question, Answer $answer): JsonResponse
    {
        if ($question->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $question->answers()->update(['is_accepted' => false]);
        $answer->update(['is_accepted' => true]);

        return response()->json(['success' => true]);
    }
}
