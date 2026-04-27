<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Mail\BecomeRevisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class RevisorController extends Controller
{
    public function index()
    {
        $article_to_check = Article::whereNull('is_accepted')->oldest()->first();
        return view('revisor.index', compact('article_to_check'));
    }

    public function accept(Article $article)
    {
        $article->setAccepted(true);
        $article->save();

        return redirect()->route('revisor.index')->with('success', 'Annuncio accettato.');
    }

    public function reject(Article $article)
    {
        $article->setAccepted(false);
        $article->save();

        return redirect()->route('revisor.index')->with('success', 'Annuncio rifiutato.');
    }

    public function becomeRevisor()
    {
        $user = auth()->user();
        Mail::to('admin@presto.it')->send(new BecomeRevisor($user));

        return redirect()->back()->with('success', 'Richiesta inviata! Ti contatteremo presto.');
    }

    public function makeRevisor(string $email)
    {
        Artisan::call('app:make-user-revisor', ['email' => $email]);
        return redirect()->back()->with('success', 'Utente reso revisore.');
    }
}
