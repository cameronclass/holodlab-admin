<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentShield\FilamentShield;

class ShieldServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        FilamentShield::configureUsing(function (FilamentShield $shield) {
            $shield->navigationGroup('Настройки')
                ->navigationIcon('heroicon-o-shield-check')
                ->navigationSort(3)
                ->navigationLabel('Роли и разрешения')
                ->sidebarCollapsibleOnDesktop();
        });
    }
}
