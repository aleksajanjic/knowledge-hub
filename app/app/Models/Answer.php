<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\User;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'question_id',
        'user_id',
        'is_accepted', // boolean to mark accepted answer
    ];

    protected $casts = [
        'is_accepted' => 'boolean',
    ];

    // Each answer belongs to a question
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // Each answer belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
