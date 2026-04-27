<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::useBootstrap();

        try {
            if (Schema::hasTable('categories')) {
                $categories = Category::orderBy('name')->get();
                View::share('categories', $categories);
            }
        } catch (\Exception $e) {
            View::share('categories', collect());
        }
    }
}
