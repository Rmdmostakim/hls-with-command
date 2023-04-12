<?php

namespace App\Providers;

use App\Services\FeedRepositoryServices;
use App\Services\LearnRepositoryServices;
use App\Services\TokenRepositoryServices;
use Illuminate\Support\ServiceProvider;

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
        // learn services register
        $this->app->singleton('LearnRepositoryServices', function ($app) {
            return new LearnRepositoryServices;
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
