<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\Physician;
use App\Models\Hospital;

class PhysicianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for($i = 0; $i < 20; $i++) {
            $physician = new Physician;
            $physician->first_name = $faker->firstName;
            $physician->middle_name = $faker->lastName;
            $physician->last_name = $faker->lastName;
            $physician->accreditation_number = $faker->bankAccountNumber;
            $physician->is_public = true;
            $physician->hospital()->associate(Hospital::find(1)->id);
            $physician->save();
        }
    }
}
