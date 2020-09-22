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
            BudgetSeeder::class,
            PersonnelSeeder::class,
            PatientSeeder::class,
            MedicalRecordSeeder::class
        ]);
    }
}
