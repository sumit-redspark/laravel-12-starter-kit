<?php

namespace App\Providers;

use App\Enums\Permission;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class PermissionServiceProvider extends ServiceProvider
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
        // Share Permission class with all views
        View::share('Permission', Permission::class);
    }
}
