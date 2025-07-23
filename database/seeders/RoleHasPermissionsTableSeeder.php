<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleHasPermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('role_has_permissions')->delete();
        
        \DB::table('role_has_permissions')->insert(array (
            0 => 
            array (
                'permission_id' => 2,
                'role_id' => 3,
            ),
            1 => 
            array (
                'permission_id' => 1,
                'role_id' => 2,
            ),
            2 => 
            array (
                'permission_id' => 1,
                'role_id' => 1,
            ),
            3 => 
            array (
                'permission_id' => 5,
                'role_id' => 1,
            ),
            4 => 
            array (
                'permission_id' => 9,
                'role_id' => 1,
            ),
            5 => 
            array (
                'permission_id' => 20,
                'role_id' => 1,
            ),
            6 => 
            array (
                'permission_id' => 10,
                'role_id' => 5,
            ),
            7 => 
            array (
                'permission_id' => 11,
                'role_id' => 5,
            ),
            8 => 
            array (
                'permission_id' => 12,
                'role_id' => 5,
            ),
            9 => 
            array (
                'permission_id' => 13,
                'role_id' => 5,
            ),
            10 => 
            array (
                'permission_id' => 14,
                'role_id' => 5,
            ),
            11 => 
            array (
                'permission_id' => 2,
                'role_id' => 3,
            ),
            12 => 
            array (
                'permission_id' => 1,
                'role_id' => 2,
            ),
            13 => 
            array (
                'permission_id' => 1,
                'role_id' => 1,
            ),
            14 => 
            array (
                'permission_id' => 5,
                'role_id' => 1,
            ),
            15 => 
            array (
                'permission_id' => 9,
                'role_id' => 1,
            ),
            16 => 
            array (
                'permission_id' => 20,
                'role_id' => 1,
            ),
            17 => 
            array (
                'permission_id' => 10,
                'role_id' => 5,
            ),
            18 => 
            array (
                'permission_id' => 11,
                'role_id' => 5,
            ),
            19 => 
            array (
                'permission_id' => 12,
                'role_id' => 5,
            ),
            20 => 
            array (
                'permission_id' => 13,
                'role_id' => 5,
            ),
            21 => 
            array (
                'permission_id' => 14,
                'role_id' => 5,
            ),
        ));
        
        
    }
}