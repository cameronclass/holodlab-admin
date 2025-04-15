<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Category;
use App\Models\Project;

class CacheServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Наблюдатель для пользователей
        User::observe(new class {
            public function saved($model)
            {
                Cache::forget('users_count');
                Cache::forget('admins_count');
            }

            public function deleted($model)
            {
                Cache::forget('users_count');
                Cache::forget('admins_count');
            }
        });

        // Наблюдатель для категорий
        Category::observe(new class {
            public function saved($model)
            {
                Cache::forget('categories_list');
                Cache::forget('categories_count');
                Cache::forget('api_categories');
            }

            public function deleted($model)
            {
                Cache::forget('categories_list');
                Cache::forget('categories_count');
                Cache::forget('api_categories');
            }
        });

        // Наблюдатель для проектов
        Project::observe(new class {
            public function saved($model)
            {
                Cache::forget('api_projects');
            }

            public function deleted($model)
            {
                Cache::forget('api_projects');
            }
        });
    }
}
