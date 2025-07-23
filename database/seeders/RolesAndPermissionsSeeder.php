<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

public function run()
{
    $admin = Role::create(['name' => 'admin']);
    $observer = Role::create(['name' => 'observer']);

    $permissions = ['view-users', 'edit-users', 'delete-users'];

    foreach ($permissions as $perm) {
        $permission = Permission::create(['name' => $perm]);
        $admin->permissions()->attach($permission);
    }

    // observer role: فقط عرض المستخدمين
    $observer->permissions()->attach(Permission::where('name', 'view-users')->first());
}

}
