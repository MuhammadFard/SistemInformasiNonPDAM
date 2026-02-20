<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
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
            // $this->registerPolicies();
            // Cek apakah request datang dari Ngrok (protokol https)
            if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
                URL::forceScheme('https');
            }
            // Role Superadmin
            Gate::define('superadmin', function ($user) {
                return $user->role === 'superadmin';
            });

            // Role Viewer
            Gate::define('viewer', function ($user) {
                return $user->role === 'viewer';
            });

            // Role Customer
            Gate::define('customer', function ($user) {
                return $user->role === 'customer';
            });
        }

}
