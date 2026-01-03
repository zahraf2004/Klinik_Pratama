<?php

namespace App\Providers;

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
        // Register view composer untuk notifikasi
        $this->app->register(\App\Providers\ViewComposerServiceProvider::class);
        
        // Register observers
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        \App\Models\Subscription::observe(\App\Observers\SubscriptionObserver::class);
    }
}
