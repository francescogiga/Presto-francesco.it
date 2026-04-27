<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ArticleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', only: ['create']),
        ];
    }

    public function create()
    {
        return view('article.create');
    }

    public function index()
    {
        $articles = Article::where('is_accepted', true)
            ->latest()
            ->paginate(6);

        return view('article.index', compact('articles'));
    }

    public function show(Article $article)
    {
        return view('article.show', compact('article'));
    }

    public function byCategory(Category $category)
    {
        $articles = $category->articles()
            ->where('is_accepted', true)
            ->latest()
            ->paginate(6);

        return view('article.byCategory', compact('articles', 'category'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $articles = Article::search($query)
            ->where('is_accepted', true)
            ->paginate(6);

        return view('article.searched', compact('articles', 'query'));
    }
}
