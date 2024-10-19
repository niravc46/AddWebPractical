<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           // Create Roles
           $adminRole = Role::create(['name' => 'Admin']);
           $authorRole = Role::create(['name' => 'Author']);

           // Create Permissions
           Permission::create(['name' => 'manage posts']);
           Permission::create(['name' => 'manage users']);

           // Assign permissions to roles
           $adminRole->givePermissionTo('manage posts', 'manage users');
           $authorRole->givePermissionTo('manage posts');
    }
}
