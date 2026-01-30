<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['title', 'content', 'user_id', 'votes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
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

    public function recalculateVotes()
    {
        $totalVotes = $this->votes()->sum('vote');
        $this->votes = $totalVotes;
        $this->save();
        return $totalVotes;
    }

    public function answers(){
        return $this->hasMany(Answer::class);
    }
}
