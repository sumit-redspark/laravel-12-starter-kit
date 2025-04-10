<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\Role as RoleEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing users
        // User::truncate();

        // Create Super Admin user
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@superadmin.com',
            'password' => Hash::make('123456'),
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole(RoleEnum::SUPER_ADMIN);

        // Create Admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456'),
            'email_verified_at' => now(),
            'created_by' => $superAdmin->id,
        ]);
        $admin->assignRole(RoleEnum::ADMIN);

        // Create normal user
        $user = User::create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => Hash::make('123456'),
            'email_verified_at' => now(),
            'created_by' => $superAdmin->id,
        ]);
        $user->assignRole(RoleEnum::USER);
    }
}
