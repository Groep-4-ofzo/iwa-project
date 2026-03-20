<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        Blade::if('role', function (string $role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });
        Blade::if('anyrole', function (string ...$roles) {
            return auth()->check() && in_array(
                auth()->user()->userRole->role,
                $roles
            );
        });
        //
    }
}
