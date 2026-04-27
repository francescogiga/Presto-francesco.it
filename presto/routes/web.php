<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\RevisorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'homepage'])->name('home');
Route::post('/locale/{lang}', [PublicController::class, 'setLocale'])->name('locale.set');

Route::get('/articles', [ArticleController::class, 'index'])->name('article.index');
Route::get('/articles/create', [ArticleController::class, 'create'])->name('article.create');
Route::get('/articles/search', [ArticleController::class, 'search'])->name('article.search');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('article.show');
Route::get('/category/{category}', [ArticleController::class, 'byCategory'])->name('article.byCategory');

Route::get('/revisor', [RevisorController::class, 'index'])->name('revisor.index')->middleware('isRevisor');
Route::patch('/revisor/{article}/accept', [RevisorController::class, 'accept'])->name('revisor.accept')->middleware('isRevisor');
Route::patch('/revisor/{article}/reject', [RevisorController::class, 'reject'])->name('revisor.reject')->middleware('isRevisor');
Route::post('/become-revisor', [RevisorController::class, 'becomeRevisor'])->name('revisor.become')->middleware('auth');
Route::get('/make-revisor/{email}', [RevisorController::class, 'makeRevisor'])->name('make.revisor');
