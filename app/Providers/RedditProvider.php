<?php

namespace App\Providers;

use App\RedditDomainBlacklist;
use App\Services\RedditService;
use Illuminate\Support\ServiceProvider;

class RedditProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(RedditService::class, function() {
            return new RedditService(env('REDDIT_URL'), false);
        });
    }
}
