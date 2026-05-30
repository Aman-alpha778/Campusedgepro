<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
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
        if (
            app()->isProduction()
            && request()->isSecure()
            && str_starts_with((string) config('app.url'), 'https://')
        ) {
            URL::forceScheme('https');
        }

        if (config('mail.default') === 'resend' && blank(config('services.resend.key'))) {
            config(['mail.default' => 'smtp']);

            Log::warning('Mail defaulted to SMTP because RESEND_API_KEY is missing.');
        }
    }
}
