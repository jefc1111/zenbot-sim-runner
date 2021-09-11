<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()    
    {   

        \Log::error($_ENV);
        \Log::error(env('APP_URL'));
        if (str_contains(env('APP_URL'), 'https')) {
            \URL::forceScheme('https');
        }
    }
}
