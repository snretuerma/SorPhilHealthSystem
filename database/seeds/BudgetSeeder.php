<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\Budget;
use App\Models\Hospital;
use Carbon\Carbon;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $budget = new Budget;
        $budget->total = 923213;
        $budget->start_date = $faker->dateTimeBetween('-4 months', Carbon::now()->format('Y-m-d'));
        $budget->end_date = $faker->dateTimeBetween('-1 months', Carbon::now()->format('Y-m-d'));
        $budget->hospital()->associate(Hospital::find(1)->id);
        $budget->save();
    }
}
