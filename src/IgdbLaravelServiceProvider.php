<?php

namespace Zinapse\IgdbLaravel;

use Illuminate\Support\ServiceProvider;
use Zinapse\IgdbLaravel\Commands\PopulateDatabase;

class IgdbLaravelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->publishes([
            __DIR__ . '/config/igdb.php' => config_path('igdb.php')
        ]);

        if ($this->app->runningInConsole())
        {
            $this->commands([
                PopulateDatabase::class
            ]);
        }
    }
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->mergeConfigFrom(__DIR__.'/config/laralocate.php', 'laralocate');
    }
}