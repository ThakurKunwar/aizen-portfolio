<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // The public JSON API: generous, but not unlimited.
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)->by($request->ip()));

        // The contact form: a person sends one or two messages, a bot sends hundreds.
        RateLimiter::for('contact', fn (Request $request) => [
            Limit::perMinute(2)->by($request->ip()),
            Limit::perDay(10)->by($request->ip()),
        ]);
    }
}
