<?php

use Illuminate\Database\Seeder;
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
    public function run()
    {
        $budget = new Budget;
        $budget->total = 923213;
        $budget->start_date = Carbon::now()->format('Y-m-d H:i:s');
        $budget->end_date = Carbon::now()->format('Y-m-d H:i:s');
        $budget->hospital()->associate(Hospital::find(1)->id);
        $budget->save();
    }
}
