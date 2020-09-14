<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'superadmin',
            ],
            [
                'name' => 'admin',
            ],
            [
                'name' => 'hospital_admin',
            ],
            [
                'name' => 'observer',
            ]
        ];

        Role::insert($roles);
    }
}
