<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $usersCount = Cache::remember('users_count', 300, function () {
            return User::count();
        });

        $adminsCount = Cache::remember('admins_count', 300, function () {
            return User::where('is_admin', true)->count();
        });

        return [
            Stat::make('Всего пользователей', $usersCount)
                ->description('Общее количество пользователей в системе')
                ->descriptionIcon('heroicon-m-users')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3])
                ->color('success'),

            Stat::make('Админы', $adminsCount)
                ->description('Количество администраторов')
                ->descriptionIcon('heroicon-m-user-circle')
                ->color('danger'),

            Stat::make('Статус системы', 'Активна')
                ->description('Обновляется каждые 15 секунд')
                ->descriptionIcon('heroicon-m-server')
                ->color('primary'),
        ];
    }
}
