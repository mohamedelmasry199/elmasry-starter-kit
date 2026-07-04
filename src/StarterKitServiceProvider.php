<?php

namespace Elmasry\StarterKit;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Elmasry\StarterKit\Console\InstallStarterKitCommand;
use Elmasry\StarterKit\Commands\CreateFilamentUserCommand;

class StarterKitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/starter-kit.php', 'starter-kit');
    }

    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'starter-kit');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'starter-kit');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Publish config
        $this->publishes([
            __DIR__ . '/../config/starter-kit.php' => config_path('starter-kit.php'),
        ], 'starter-kit-config');

        // Publish translations
        $this->publishes([
            __DIR__ . '/../resources/lang' => lang_path('vendor/starter-kit'),
        ], 'starter-kit-lang');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/starter-kit'),
        ], 'starter-kit-views');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'starter-kit-migrations');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallStarterKitCommand::class,
                CreateFilamentUserCommand::class,
            ]);
        }

        // Use Tailwind pagination
        Paginator::useTailwind();
    }
}
