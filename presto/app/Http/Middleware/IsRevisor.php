<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsRevisor
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->is_revisor) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Non hai i permessi per accedere a questa sezione.');
    }
}
