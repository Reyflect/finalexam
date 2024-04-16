<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    private $guardName = 'admin';
    /**
     * Run the database seeds.
     */
    public function run(): void
    {/*
        $permissions = [
            [
                'label' => 'Manage Orders Module',
                'name' => 'manage-orders-module',
            ],
        ];
        $superadmin = Role::create([
            'name' => 'Superadmin',
        ]);*/

        $role = Role::create(['name' => 'Superadmin']);
        $permission = Permission::create(['name' => 'Manage Orders Module']);
        $role->givePermissionTo($permission);
        $permission->assignRole($role);


        $superadmin = User::where('email', 'rey@gmail.com')->first();

        if ($superadmin) {
            $superadmin->assignRole($role);
        }

        $role = Role::create(['name' => 'normaladmin']);
        $permission = Permission::create(['name' => 'Manage Products']);
        $role->givePermissionTo($permission);
        $permission->assignRole($role);


        $normaladmin = User::where('email', 'admin@gmail.com')->first();

        if ($normaladmin) {
            $normaladmin->assignRole($role);
        }
    }
}
