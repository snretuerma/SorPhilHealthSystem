<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\Patient;
use App\Models\Hospital;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i = 0; $i < 50; $i++) {
            $patient = new Patient;
            $patient->first_name = $faker->firstName;
            $patient->middle_name = $faker->lastName;
            $patient->last_name = $faker->lastName;
            $patient->name_suffix = null;
            $patient->sex = rand(0, 1);
            $patient->birthdate = $faker->date;
            $patient->marital_status = rand(0, 4);
            $patient->philhealth_number = $faker->ssn;
            $patient->hospital()->associate(Hospital::find(1)->id);
            $patient->save();
        }
    }
}
