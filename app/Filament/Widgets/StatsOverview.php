<?php

namespace App\Filament\Widgets;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $usersCount = User::count();
        $adminsCount = User::where('is_admin', true)->count();
        $brandsCount = Brand::count();
        $projectsCount = Project::count();
        $categoriesCount = Category::count();

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
            Stat::make('Бренды', $brandsCount)
                ->description('Всего брендов в системе')
                ->descriptionIcon('heroicon-o-tag')
                ->color('primary'),
            Stat::make('Наши Работы', $projectsCount)
                ->description('Всего работ в системе')
                ->descriptionIcon('heroicon-o-rectangle-stack')
                ->color('success'),
            Stat::make('Категории', $categoriesCount)
                ->description('Всего категорий в системе')
                ->descriptionIcon('heroicon-o-squares-2x2')
                ->color('info'),
        ];
    }
}
