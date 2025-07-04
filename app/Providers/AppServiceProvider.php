<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

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
        // Passport
        Passport::enablePasswordGrant();
        Passport::tokensExpireIn(now()->addMinutes(config('passport.access_token_ttl')));
        Passport::refreshTokensExpireIn(now()->addMinutes(config('passport.refresh_token_ttl')));
    }
}
