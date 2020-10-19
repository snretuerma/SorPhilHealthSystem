<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\Personnel;
use App\Models\Hospital;

class PersonnelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for($i = 0; $i < 20; $i++) {
            $personnel = new Personnel;
            $personnel->first_name = $faker->firstName;
            $personnel->middle_name = $faker->lastName;
            $personnel->last_name = $faker->lastName;
            $personnel->name_suffix = null;
            $personnel->sex = rand(0, 1);
            $personnel->birthdate = $faker->date;
            $personnel->is_private = rand(0, 1) === 1 ? true : false;
            $personnel->designation = rand(0, 1) === 1 ? true : false;
            $personnel->hospital()->associate(Hospital::find(1)->id);
            $personnel->save();
        }
    }
}
