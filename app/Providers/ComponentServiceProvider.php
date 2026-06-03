<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class ComponentServiceProvider extends ServiceProvider
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
        // Register admin components namespace
        Blade::componentNamespace('App\\View\\Components\\Admin', 'admin');

        // Register admin layout
        Blade::component('admin.layouts.app', 'admin.layouts.app');

        // Register admin components
        Blade::component('admin.components.stat-card', 'admin.components.stat-card');
    }
}