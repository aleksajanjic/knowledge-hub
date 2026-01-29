<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Contracts\View\View;


class HomeController extends Controller
{
    public function index(): View
    {
        $questions = Question::with('user')->latest()->get();

        return view('home', compact('questions'));
    }
}
