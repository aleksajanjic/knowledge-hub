<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\User;

class ReputationService
{
    const QUESTION_UPVOTE = 5;
    const ANSWER_UPVOTE = 10;
    const ANSWER_ACCEPTED = 15;
    const DOWNVOTE_RECEIVED = -2;

    public function recalculateUserReputation(User $user): int
    {
        $reputation = 0;

        $questionVotes = $user->questions()
            ->withSum("votes as total_votes",  'vote')
            ->get()
            ->sum("total_votes");
        $reputation += $questionVotes * self::QUESTION_UPVOTE;

        $answerVotes = $user->answers()
            ->withSum("votes as total_votes", "vote")
            ->get()
            ->sum("total_votes");
        $reputation += $answerVotes * self::ANSWER_UPVOTE;

        $acceptedAnswers = $user->answers()
            ->where("is_accepted", true)
            ->count();
        $acceptedAnswers += $acceptedAnswers * self::ANSWER_ACCEPTED;

        $user->update(['reputation' => max(0, $reputation)]);

        return $user->reputation;
    }

    public function updateReputationAfterVote($votable, int $oldVote, int $newVote): void
    {
        $user = $votable->user;
        if (!$user) {
            return;
        }

        $points = 0;
        $isQuestion = $votable instanceof Question;
        $pointsPerVote = $isQuestion ? self::QUESTION_UPVOTE : self::ANSWER_UPVOTE;

        if ($oldVote === 0 && $newVote === 1) {
            $points = $pointsPerVote;
        } elseif ($oldVote === 0 && $newVote === -1) {
            $points = self::DOWNVOTE_RECEIVED;
        } elseif ($oldVote === 1 && $newVote === 0) {
            $points = -$pointsPerVote;
        } elseif ($oldVote === -1 && $newVote === 0) {
            $points = -self::DOWNVOTE_RECEIVED;
        } elseif ($oldVote === 1 && $newVote === -1) {
            $points = -$pointsPerVote + self::DOWNVOTE_RECEIVED;
        } elseif ($oldVote === -1 && $newVote === 1) {
            $points = $pointsPerVote - self::DOWNVOTE_RECEIVED;
        }

        $user->increment('reputation', $points);
        $user->update(['reputation' => max(0, $user->reputation)]);
    }

    public function updateReputationAfterAccept(Answer $answer, bool $isAccepted): void
    {
        $user = $answer->user;
        if (!$user) return;

        $points = $isAccepted ? self::ANSWER_ACCEPTED : -self::ANSWER_ACCEPTED;
        $user->increment('reputation', $points);
        $user->update(['reputation' => max(0, $user->reputation)]);
    }
}
