<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use App\Services\ReputationService;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    protected $reputationService;

    public function __construct(ReputationService $reputationService)
    {
        $this->reputationService = $reputationService;
    }

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

    public function upvote(Answer $answer)
    {
        $user = auth()->user();
        $existingVote = $answer->votes()->where('user_id', $user->id)->first();

        $oldVote = $existingVote ? $existingVote->vote : 0;
        $newVote = ($oldVote === 1) ? 0 : 1;

        if ($existingVote) {
            if ($newVote === 0) {
                $existingVote->delete();
            } else {
                $existingVote->update(['vote' => $newVote]);
            }
        } else {
            $answer->votes()->create([
                'user_id' => $user->id,
                'vote' => $newVote
            ]);
        }

        $totalVotes = $answer->votes()->sum('vote');
        $answer->update(['votes' => $totalVotes]);

        $this->reputationService->updateReputationAfterVote($answer, $oldVote, $newVote);

        return response()->json([
            'votes' => $answer->fresh()->votes,
            'userVote' => $newVote,
            'authorReputation' => $answer->user->fresh()->reputation
        ]);
    }

    public function downvote(Answer $answer)
    {
        $user = auth()->user();
        $existingVote = $answer->votes()->where('user_id', $user->id)->first();

        $oldVote = $existingVote ? $existingVote->vote : 0;
        $newVote = ($oldVote === -1) ? 0 : -1;

        if ($existingVote) {
            if ($newVote === 0) {
                $existingVote->delete();
            } else {
                $existingVote->update(['vote' => $newVote]);
            }
        } else {
            $answer->votes()->create([
                'user_id' => $user->id,
                'vote' => $newVote
            ]);
        }

        $totalVotes = $answer->votes()->sum('vote');
        $answer->update(['votes' => $totalVotes]);

        $this->reputationService->updateReputationAfterVote($answer, $oldVote, $newVote);

        return response()->json([
            'votes' => $answer->fresh()->votes,
            'userVote' => $newVote,
            'authorReputation' => $answer->user->fresh()->reputation
        ]);
    }

    public function edit(Answer $answer)
    {
        $this->authorize('update', $answer);

        try {
            return view('components.questions.answer-edit-modal', compact('answer'));
        } catch (\Throwable $e) {
            report($e);
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()], 500);
            }
            throw $e;
        }
    }

    public function update(Request $request, Answer $answer)
    {
        $this->authorize('update', $answer);

        $validated = $request->validate([
            'body' => 'required|string|min:10',
        ]);

        $answer->update(['body' => $validated['body']]);

        return redirect()->route('questions.index')
            ->with('success', __('Answer updated.'));
    }

    public function acceptAnswer($questionId, $answerId)
    {
        $question = Question::findOrFail($questionId);
        $answer = Answer::findOrFail($answerId);

        $this->authorize('accept', $answer);

        $wasAccepted = $answer->is_accepted;

        if ($wasAccepted) {
            $answer->update(['is_accepted' => false]);
            $this->reputationService->updateReputationAfterAccept($answer, false);
            return response()->json([
                'success' => true,
                'accepted' => false,
                'authorReputation' => $answer->user->fresh()->reputation
            ]);
        }

        $question->answers()->update(['is_accepted' => false]);
        $answer->update(['is_accepted' => true]);
        $this->reputationService->updateReputationAfterAccept($answer, true);

        return response()->json([
            'success' => true,
            'accepted' => true,
            'authorReputation' => $answer->user->fresh()->reputation
        ]);
    }
}
