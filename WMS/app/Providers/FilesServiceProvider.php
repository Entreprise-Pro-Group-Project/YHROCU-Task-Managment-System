<?php

namespace App\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class FilesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Filesystem::class, function () {
            return new Filesystem();
        });

        $this->app->alias(Filesystem::class, 'files');
    }

    public function boot()
    {
        //
    }
}
