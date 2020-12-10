<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;
use Faker\Generator as Faker;

use App\Models\Doctor;
use App\Models\Hospital;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeder for the doctors table.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for($index = 0; $index <5; $index++) {
            $doctor = new Doctor;
            $doctor->hospital()->associate(Hospital::find(1)->id);
            $doctor->name = $faker->lastName.', '.$faker->firstName.' '.$faker->lastName;
            $doctor->is_active = true;
            $doctor->is_parttime = false;
            $doctor->save();
        }

        for($index = 0; $index < 5; $index++) {
            $doctor = new Doctor;
            $doctor->hospital()->associate(Hospital::find(1)->id);
            $doctor->name = $faker->lastName.', '.$faker->firstName.' '.$faker->lastName;
            $doctor->is_active = true;
            $doctor->is_parttime = true;
            $doctor->save();
        }

        for($index = 0; $index < 5; $index++) {
            $doctor = new Doctor;
            $doctor->hospital()->associate(Hospital::find(1)->id);
            $doctor->name = $faker->lastName.', '.$faker->firstName.' '.$faker->lastName;
            $doctor->is_active = rand(0,1);
            $doctor->is_parttime = rand(0,1);
            $doctor->save();
        }
    }
}
