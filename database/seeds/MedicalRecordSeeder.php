<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Contribution;
use App\Models\Personnel;
use Carbon\Carbon;

class MedicalRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $patients = Patient::get()->all();
        foreach($patients as $patient)
        {
            $record = new MedicalRecord;
            $record->patient()->associate($patient->id);
            $record->admission_date = $faker->dateTimeBetween('-4 months', Carbon::now());
            $record->discharge_date = $faker->dateTimeBetween('-1 months', Carbon::now());
            $record->final_diagnosis = "COVID";
            $record->record_type = rand(1,2) === 1 ? 'Credited' : 'IRM Deduction';
            $record->save();
        }

        $records = MedicalRecord::get()->all();
        foreach($records as $record)
        {
            $contribution = new Contribution;
            $contribution->type = 'medical';
            $contribution->contribution = 'admitting';
            $contribution->credit = rand(1000,9999);;
            $contribution->status = 'paid';
            $contribution->is_private = false;
            $contribution->save();
            $record->personnels()->attach(Personnel::find(rand(0,10)), ['contribution_id' => $contribution->id]);
        }
    }
}
