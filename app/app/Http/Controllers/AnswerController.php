<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function store(Request $request, Question $question)
    {
        $validated = $request->validate([
            'content' => 'required|string|min:10'
        ]);

        $answer = $question->answers()->create([
            'body' => $validated['content'],
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'answer_id' => $answer->id
        ]);
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

    // public function accept(Question $question, Answer $answer): JsonResponse
    // {
    //     if ($question->user_id !== auth()->id()) {
    //         return response()->json(['error' => 'Unauthorized'], 403);
    //     }
    //
    //     $question->answers()->update(['is_accepted' => false]);
    //     $answer->update(['is_accepted' => true]);
    //
    //     return response()->json(['success' => true]);
    // }

    // public function acceptAnswer($questionId, $answerId)
    // {
    //     $question = Question::findOrFail($questionId);
    //     $answer = Answer::findOrFail($answerId);
    //
    //     // Only question owner can accept
    //     if (auth()->id() !== $question->user_id) {
    //         return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    //     }
    //
    //     // Unmark previously accepted answer
    //     $question->answers()->update(['is_accepted' => false]);
    //
    //     // Mark this answer as accepted
    //     $answer->update(['is_accepted' => true]);
    //
    //     return response()->json(['success' => true]);
    // }

    public function acceptAnswer($questionId, $answerId)
    {
        $question = \App\Models\Question::findOrFail($questionId);
        $answer = \App\Models\Answer::findOrFail($answerId);

        if (auth()->id() !== $question->user_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($answer->is_accepted) {
            // Unmark this answer
            $answer->update(['is_accepted' => false]);
            return response()->json(['success' => true, 'accepted' => false]);
        }

        // Unmark previously accepted answer
        $question->answers()->update(['is_accepted' => false]);

        // Mark this one
        $answer->update(['is_accepted' => true]);

        return response()->json(['success' => true, 'accepted' => true]);
    }
}
