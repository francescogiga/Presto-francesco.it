<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function homepage()
    {
        $articles = Article::where('is_accepted', true)
            ->latest()
            ->take(6)
            ->get();

        return view('welcome', compact('articles'));
    }

    public function setLocale(string $lang)
    {
        session(['locale' => $lang]);
        return redirect()->back();
    }
}
