<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Spatie cache clear
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Guard (change if you use a custom one like 'admin')
        $guard = config('auth.defaults.guard', 'web');

        // --- Modules: base first, then children (singular action names) ---
        $modules = [
            [
                'base' => 'page management',
                'children' => ['page add', 'page edit', 'page delete'],
            ],
            [
                'base' => 'gallerymanager',
                'children' => ['gallerymanager add', 'gallerymanager edit', 'gallerymanager delete'],
            ],
            [
                'base' => 'slider management',
                'children' => ['slider add', 'slider edit', 'slider delete'],
            ],
            [
                'base' => 'menus management',
                'children' => ['menus add', 'menus edit', 'menus delete'],
            ],
            [
                'base' => 'contactmessages',
                'children' => ['contactmessage delete'],
            ],
            [
                'base' => 'user management',
                'children' => ['user add', 'user edit', 'user delete'],
            ],
            [
                'base' => 'role management',
                'children' => ['role add', 'role edit', 'role delete'],
            ],
            [
                'base' => 'permission management',
                'children' => ['permission add', 'permission edit', 'permission delete'],
            ],
        ];

        // 1) Create permissions in desired order (base -> children)
        foreach ($modules as $m) {
            // base
            Permission::firstOrCreate([
                'name' => $m['base'],
                'guard_name' => $guard,
            ]);

            // children
            foreach ($m['children'] as $child) {
                Permission::firstOrCreate([
                    'name' => $child,
                    'guard_name' => $guard,
                ]);
            }
        }

        // 2) Create Admin role (same guard) & assign ALL permissions
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => $guard]);
        $admin->syncPermissions(Permission::where('guard_name', $guard)->get());

        // Cache refresh
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
