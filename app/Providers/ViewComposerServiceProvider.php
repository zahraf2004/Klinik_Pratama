<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share notifikasi ke navbar untuk admin
        View::composer('partials.navbar', function ($view) {
            if (Auth::check() && Auth::user()->role === 'admin') {
                $notifications = Notification::forAdmin(Auth::id())
                    ->latest()
                    ->take(5)
                    ->get();

                $unreadCount = Notification::forAdmin(Auth::id())
                    ->unread()
                    ->count();

                $view->with([
                    'notifications' => $notifications,
                    'unreadCount' => $unreadCount
                ]);
            }
        });
    }
}
