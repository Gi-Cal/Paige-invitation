<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL; 
use Illuminate\Support\Facades\Mail;
use App\Mail\SendGridTransport;

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
        // Fix for "Specified key was too long" error
        Schema::defaultStringLength(191);
        
        // ✅ Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

                Mail::extend('sendgrid', function () {
            return new SendGridTransport(config('services.sendgrid.key'));
        });
    }
}