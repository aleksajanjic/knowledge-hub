<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Services\ReputationService;
use Symfony\Component\HttpFoundation\JsonResponse;

class VoteController extends Controller
{
    protected $reputationService;

    public function __construct(ReputationService $reputationService)
    {
        $this->reputationService = $reputationService;
    }

    public function upvote(Question $question): JsonResponse
    {
        $user = auth()->user();
        $existingVote = $question->votes()->where("user_id", $user->id)->first();

        $oldVote = $existingVote ? $existingVote->vote : 0;
        $newVote = ($oldVote === 1) ? 0 : 1;

        if ($existingVote) {
            $newVote === 0 ? $existingVote->delete() : $existingVote->update(['vote' => $newVote]);
        } else {
            $question->votes()->create([
                'user_id' => $user->id,
                'vote' => $newVote
            ]);
        }

        $totalVotes = $question->votes()->sum('vote');
        $question->update(['votes' => $totalVotes]);

        $this->reputationService->updateReputationAfterVote($question, $oldVote, $newVote);

        return response()->json([
            'votes' => $question->fresh()->votes,
            'userVote' => $newVote,
            'authorReputation' => $question->user->fresh()->reputation
        ]);
    }

    public function downvote(Question $question): JsonResponse
    {
        $user = auth()->user();
        $existingVote = $question->votes()->where('user_id', $user->id)->first();

        $oldVote = $existingVote ? $existingVote->vote : 0;
        $newVote = ($oldVote === -1) ? 0 : -1;

        if ($existingVote) {
            if ($newVote === 0) {
                $existingVote->delete();
            } else {
                $existingVote->update(['vote' => $newVote]);
            }
        } else {
            $question->votes()->create([
                'user_id' => $user->id,
                'vote' => $newVote
            ]);
        }

        $totalVotes = $question->votes()->sum('vote');
        $question->update(['votes' => $totalVotes]);

        $this->reputationService->updateReputationAfterVote($question, $oldVote, $newVote);

        return response()->json([
            'votes' => $question->fresh()->votes,
            'userVote' => $newVote,
            'authorReputation' => $question->user->fresh()->reputation
        ]);
    }
}
