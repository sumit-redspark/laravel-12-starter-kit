<?php

namespace Database\Seeders;

use App\Enums\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing roles
        // Role::truncate();

        // Get the super admin user
        $superAdmin = User::where('email', 'superadmin@superadmin.com')->first();

        // Create Super Admin role with all permissions
        $superAdminRole = Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'web',
            // 'created_by' => $superAdmin->id ?? null
        ]);
        $superAdminRole->givePermissionTo(Permission::all());

        // Create Admin role with most permissions
        $adminRole = Role::create([
            'name' => 'Admin',
            'guard_name' => 'web',
            // 'created_by' => $superAdmin->id ?? null
        ]);
        $adminRole->givePermissionTo([
            Permission::USER_VIEW,
            Permission::USER_CREATE,
            Permission::USER_EDIT,
            Permission::USER_DELETE,
            Permission::ROLE_VIEW,
            Permission::ROLE_CREATE,
            Permission::ROLE_EDIT,
            Permission::ROLE_DELETE,
            Permission::PERMISSION_VIEW,
        ]);

        // Create User role with basic permissions
        $userRole = Role::create([
            'name' => 'User',
            'guard_name' => 'web',
            // 'created_by' => $superAdmin->id ?? null
        ]);
        $userRole->givePermissionTo([
            Permission::USER_VIEW,
        ]);
    }
}
