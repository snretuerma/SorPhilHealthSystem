<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            HospitalSeeder::class,
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            // DoctorSeeder::class,
            // CreditRecordSeeder::class,
        ]);
    }
}
