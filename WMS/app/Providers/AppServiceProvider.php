<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    // In App\Providers\AppServiceProvider.php
public function register()
{
    if (! $this->app->bound('blade.compiler')) {
        $this->app->singleton('blade.compiler', function ($app) {
            return new \Illuminate\View\Compilers\BladeCompiler(
                $app['files'],
                $app['config']['view.compiled']
            );
        });
    }
}


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        \App\Models\Task::observe(\App\Observers\TaskObserver::class);
        \App\Models\TaskComment::observe(\App\Observers\TaskCommentObserver::class);
        \App\Models\Project::observe(\App\Observers\ProjectObserver::class);
    }
}
