<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share categories only to the main layout so navbar can render categories
        View::composer('layouts.app', function ($view) {
            try {
                $categories = Category::orderBy('category_name')->get();
            } catch (\Throwable $e) {
                $categories = collect();
            }

            $view->with('categories', $categories);
        });
    }
}
