<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'create-role', 'display_name' => 'Create Role'],
            ['name' => 'edit-role', 'display_name' => 'Edit Role'],
            ['name' => 'delete-role', 'display_name' => 'Delete Role'],
            ['name' => 'create-user', 'display_name' => 'Create User'],
            ['name' => 'edit-user', 'display_name' => 'Edit User'],
            ['name' => 'delete-user', 'display_name' => 'Delete User'],
            

        ];

        // Looping and Inserting Permissions into Permission Table
        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'display_name' => $permission['display_name'],
            ]);
        }
    }
}
