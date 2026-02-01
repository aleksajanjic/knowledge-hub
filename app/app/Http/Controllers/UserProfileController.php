<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use App\Models\Vote;
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
            'total_upvotes' => $this->getTotalUpvotes($user),
        ];

        return view('profile.show', compact(
            'user',
            'stats'
        ));
    }

    /**
     * Get total upvotes received by user
     */
    private function getTotalUpvotes(User $user): int
    {
        $questionUpvotes = Vote::where('votable_type', 'Question')
            ->where('vote', 1)
            ->whereIn('votable_id', Question::where('user_id', $user->id)->pluck('id'))
            ->count();

        $answerUpvotes = Vote::where('votable_type', "Answer")
            ->where('vote', 1)
            ->whereIn('votable_id', Answer::where('user_id', $user->id)->pluck('id'))
            ->count();

        return $questionUpvotes + $answerUpvotes;
    }
}
