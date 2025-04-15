<?php

namespace Database\Seeders;

use App\Enums\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission as PermissionModel;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
     /**
      * Run the database seeds.
      */
     public function run(): void
     {
          // Clear existing permissions
          PermissionModel::truncate();

          // Create all permissions from the Permission enum
          foreach (Permission::all() as $permission)
          {
               PermissionModel::create([
                    'name' => $permission,
                    'guard_name' => 'web'
               ]);
          }

          // Assign all permissions to Super Admin role
          $superAdmin = Role::where('name', 'Super Admin')->first();
          if ($superAdmin)
          {
               $superAdmin->syncPermissions(Permission::all());
          }
     }
}
