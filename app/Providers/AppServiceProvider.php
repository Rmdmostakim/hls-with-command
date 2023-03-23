<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\FeedRepositoryServices;
use App\Services\TokenRepositoryServices;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // token services register
        $this->app->singleton('TokenRepositoryServices', function ($app) {
            return new TokenRepositoryServices;
        });
        // feed services register
        $this->app->singleton('FeedRepositoryServices', function ($app) {
            return new FeedRepositoryServices;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
