<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Roles
        $roles = [
            'Super Admin',
            'India Trade Manager',
            'Angola Operations Manager',
            'Switzerland Desk',
            'Finance Officer',
            'Compliance Officer',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // 2. Create Permissions (Initial structure)
        $permissions = [
            'view dashboard',
            'manage users',
            'manage roles',
            'manage buyers',
            'manage suppliers',
            'manage transactions',
            'approve transactions',
            'manage lcs',
            'manage shipments',
            'view analytics',
            'access vault',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. Assign Permissions
        $superAdmin = Role::findByName('Super Admin');
        $superAdmin->givePermissionTo(Permission::all());

        $indiaManager = Role::findByName('India Trade Manager');
        $indiaManager->givePermissionTo([
            'view dashboard',
            'manage buyers',
            'manage suppliers',
            'manage transactions',
            'view analytics',
        ]);

        // 4. Create Super Admin User
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'superadmin@tradeos.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        
        $user->assignRole('Super Admin');

        // ... Assign others as appropriate (placeholder for now)
    }
}
