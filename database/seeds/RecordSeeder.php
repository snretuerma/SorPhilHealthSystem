<?php

use Illuminate\Database\Seeder;
use App\Models\Record;
use App\Models\Physician;
use App\Models\ParticipationType;
use App\Models\Hospital;
use App\Models\Budget;
use Faker\Generator as Faker;

class RecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $record = new Record;
        $record->hospital()->associate(Hospital::find(1)->id);
        $record->patient_first_name = $faker->firstName;
        $record->patient_last_name = $faker->lastName;
        $record->patient_middle_name = $faker->lastName;
        $record->is_credited = false;
        $record->patient_in = $faker->dateTimeBetween($startDate = '-2 months', $endDate = 'now', $timezone = null);
        $record->patient_out = $faker->dateTimeBetween($startDate = '-1 months', $endDate = 'now', $timezone = null);
        $record->total_pf = $faker->randomNumber(6);
        $record->save();
        $record->budgets()->attach(Budget::find(1)->id);
        $record->physicians()->attach(Physician::find(3)->id);
        $record->pivot->participation_type_id = 1;
        $record->pivot->amount = $faker->randomNumber(5 );
        $record->pivot->save();
    }
}
