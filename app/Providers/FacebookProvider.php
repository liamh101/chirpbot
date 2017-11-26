<?php

namespace App\Providers;

use Facebook\Facebook;
use Illuminate\Support\ServiceProvider;

class FacebookProvider extends ServiceProvider
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
        $this->app->singleton(Facebook::class, function() {
            return new Facebook(
                [
                    'app_id' => env('FACEBOOK_APP_ID'),
                    'app_secret' => env('FACEBOOK_APP_SECRET'),
                    'default_graph_version' => 'v2.10',
                    'default_access_token' => env('FACEBOOK_TOKEN')
                ]
            );
        });
    }
}
