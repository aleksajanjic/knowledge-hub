<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['body', 'question_id', 'user_id', 'votes', 'is_accepted'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function userVote($userId)
    {
        return $this->votes()->where('user_id', $userId)->first();
    }

    public function upvote($userId)
    {
        $existingVote = $this->userVote($userId);

        if ($existingVote) {
            if ($existingVote->vote == 1) {
                $existingVote->delete();
                $this->decrement('votes');
                return;
            } else {
                $existingVote->update(['vote' => 1]);
                $this->votes = $this->votes + 2;
                $this->save();
                return;
            }
        } else {
            $this->votes()->create([
                'user_id' => $userId,
                'vote' => 1
            ]);
            $this->increment('votes');
        }
    }

    public function downvote($userId)
    {
        $existingVote = $this->userVote($userId);

        if ($existingVote) {
            if ($existingVote->vote == -1) {
                $existingVote->delete();
                $this->increment('votes');
                return;
            } else {
                $existingVote->update(['vote' => -1]);
                $this->votes = $this->votes - 2;
                $this->save();
                return;
            }
        } else {
            $this->votes()->create([
                'user_id' => $userId,
                'vote' => -1
            ]);
            $this->decrement('votes');
        }
    }
}
