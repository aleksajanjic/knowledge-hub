<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::paginate(20);
        $tags = Tag::withCount("questions")->paginate(20);

        return view("admin.dashboard", compact("users", "tags"));
    }
}
