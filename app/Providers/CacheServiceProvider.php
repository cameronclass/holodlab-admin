<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class CacheServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
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
    }
}
