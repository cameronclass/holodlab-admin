<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем роль администратора
        $adminRole = Role::create(['name' => 'admin']);

        // Создаем базовые разрешения
        $permissions = [
            'view_admin_panel',
            'manage_users',
            'manage_roles',
            'manage_permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Назначаем все разрешения роли администратора
        $adminRole->givePermissionTo(Permission::all());

        // Создаем администратора
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'is_admin' => true,
        ]);

        // Назначаем роль администратора
        $admin->assignRole($adminRole);
    }
}
