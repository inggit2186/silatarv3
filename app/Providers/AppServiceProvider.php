<?php

namespace App\Providers;

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
        // Load admin routes manually
        $this->loadRoutes();
    }

    /**
     * Load admin routes with custom prefix
     */
    private function loadRoutes(): void
    {
        $router = $this->app->make('router');

        // Register admin routes with prefix (no 'as' to avoid double prefix)
        $router->group([
            'prefix' => 'admin',
            'middleware' => ['web', 'auth', 'admin'],
        ], function ($router) {
            require base_path('routes/admin.php');
        });
    }
}
