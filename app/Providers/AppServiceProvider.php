<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

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
        // throws an error on N+1 queries while developing
        // N+1 = 1 query for the posts + 1 more query for EACH post's author
        // so if I forget with() the page crashes and I notice right away
        // only when not in production so real users never see the error
        Model::preventLazyLoading(! app()->isProduction());
    }
}
