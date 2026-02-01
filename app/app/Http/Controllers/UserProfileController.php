<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    /**
     * Display the user's public profile
     */
    public function show(User $user)
    {
        $user = Auth::user();

        if (!$user) {
            dd('User is null', auth()->check(), session()->all());
        }


        $user->loadCount(['questions', 'answers']);

        $stats = [
            'questions_asked' => $user->questions_count,
            'answers_given' => $user->answers_count,
            'accepted_answers' => $user->answers()->where('is_accepted', true)->count(),
            'total_upvotes' => '10'
            // 'total_upvotes' => $this->getTotalUpvotes($user),
        ];

        return view('profile.show', compact(
            'user',
            'stats'
        ));
    }

    /**
     * Get total upvotes received by user
     */
    // private function getTotalUpvotes(User $user): int
    // {
    //     $questionUpvotes = \DB::table('votes')
    //         ->join('questions', 'votes.votable_id', '=', 'questions.id')
    //         ->where('votes.votable_type', 'App\Models\Question')
    //         ->where('questions.user_id', $user->id)
    //         ->where('votes.vote_type', 'upvote')
    //         ->count();
    //
    //     $answerUpvotes = \DB::table('votes')
    //         ->join('answers', 'votes.votable_id', '=', 'answers.id')
    //         ->where('votes.votable_type', 'App\Models\Answer')
    //         ->where('answers.user_id', $user->id)
    //         ->where('votes.vote_type', 'upvote')
    //         ->count();
    //
    //     return $questionUpvotes + $answerUpvotes;
    // }
}
