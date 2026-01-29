<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function upvote(Question $question)
    {
        $question->upvote(auth()->id());

        return response()->json([
            'votes' => $question->fresh()->votes,
            'userVote' => $question->userVote(auth()->id())?->vote
        ]);
    }

    public function downvote(Question $question)
    {
        $question->downvote(auth()->id());

        return response()->json([
            'votes' => $question->fresh()->votes,
            'userVote' => $question->userVote(auth()->id())?->vote
        ]);
    }
}
