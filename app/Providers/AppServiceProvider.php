<?php

namespace App\Providers;

use CloudCreativity\LaravelStripe\LaravelStripe;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        LaravelStripe::withoutMigrations();
    }
}
