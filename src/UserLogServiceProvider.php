<?php

namespace Skycoder\UserLog;

use Illuminate\Support\ServiceProvider;

class UserLogServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/views', 'user-log');


        $this->publishes(
            [__DIR__ . '/views' => base_path('resources/views/vendor/user-log')],
            'user-log'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
}
