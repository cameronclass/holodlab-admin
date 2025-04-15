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
        // Динамически устанавливаем URL для всех публичных дисков с учетом текущего хоста и порта
        if (request()->server('SERVER_PORT') && request()->server('SERVER_PORT') != '80') {
            $url = request()->getScheme() . '://' . request()->getHost() . ':' . request()->server('SERVER_PORT');

            // Получаем все диски из конфигурации
            $disks = config('filesystems.disks');

            // Обновляем URL для всех публичных дисков
            foreach ($disks as $diskName => $diskConfig) {
                if (isset($diskConfig['visibility']) && $diskConfig['visibility'] === 'public') {
                    $storagePath = str_replace(env('APP_URL'), '', $diskConfig['url']);
                    config(['filesystems.disks.' . $diskName . '.url' => $url . $storagePath]);
                }
            }
        }
    }
}