<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Conversation;
use App\Policies\ConversationPolicy;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */


    public function register(): void
    {
        //
    }
    protected $policies = [
        Conversation::class => ConversationPolicy::class,
    ];
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       view()->composer('*', function ($view) {
        if (auth()->check()) {
            $activeTheme = auth()->user()->themes()->wherePivot('is_active', true)->first();
            $view->with('activeTheme', $activeTheme);
        }
    });
    }
}
