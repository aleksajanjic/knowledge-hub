<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\JsonResponse;

class BookmarkController extends Controller
{
    public function toggle(Question $question): JsonResponse
    {
        $user = auth()->user();
        $bookmark = $user->bookmarks()->where('question_id', $question->id)->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json(['bookmarked' => false]);
        }

        $user->bookmarks()->create(['question_id' => $question->id]);
        return response()->json(['bookmarked' => true]);
    }
}
