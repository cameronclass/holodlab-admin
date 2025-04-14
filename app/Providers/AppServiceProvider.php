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
        // Динамически устанавливаем URL для хранилища с учетом текущего хоста и порта
        if (request()->server('SERVER_PORT') && request()->server('SERVER_PORT') != '80') {
            $url = request()->getScheme() . '://' . request()->getHost() . ':' . request()->server('SERVER_PORT');
            config(['filesystems.disks.categories.url' => $url . '/storage/categories']);
        }
    }
}