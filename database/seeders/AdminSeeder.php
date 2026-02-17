<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Spatie cache clear (safe)
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $user = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'Admin', 'password' => Hash::make('admin@1122')]
        );

        // Guard same rakhein jo aap users ke liye use karte ho (aksar 'web')
        $guard = config('auth.defaults.guard', 'web');

        // Ensure role exists
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => $guard]);

        // Assign role to user
        $user->syncRoles([$adminRole]);

        // cache refresh
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
