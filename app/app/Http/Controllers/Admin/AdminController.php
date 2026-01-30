<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\User;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    // public function index(Request $request)
    // {
    //     $usersPage = $request->attributes->get('users_page', 1);
    //     $tagsPage = $request->attributes->get('tags_page', 1);
    //
    //     $users = User::paginate(20, ['*'], 'users_page', $usersPage);
    //     $tags = Tag::withCount('questions')
    //         ->latest()
    //         ->paginate(20, ['*'], "tags_page", $tagsPage);
    //
    //     return view("admin.dashboard", compact("users", "tags"));
    // }

    public function index()
    {
        return view("admin.dashboard");
    }
}
