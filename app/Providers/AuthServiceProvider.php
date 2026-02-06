<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        Gate::define('manage-finances', function ($user) {
            return $user->hasAnyRole(['admin', 'secretariat']);
        });

        Gate::define('manage-pedagogy', function ($user) {
            return $user->hasAnyRole(['admin', 'direction']);
        });

        Gate::define('manage-users', function ($user) {
            return $user->hasRole('admin');
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
