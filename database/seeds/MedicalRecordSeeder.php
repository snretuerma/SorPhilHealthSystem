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
        $diagnosis = [
            'Acute cholecystitis', 'Food poisoning', 'Flu', 'Heart failure',
            'HIV', 'Lung cancer', 'Multiple myeloma', '2019-nCoV', 'Ebola',
            'Hantavirus', 'Hepatitis A', 'Rabies', 'West Nile Virus', 'Zika',
            'Measles', 'CRE', 'Enterovirus D68', 'MRSA', 'Shigellosis'
        ];
        $patients = Patient::get()->all();
        foreach($patients as $patient)
        {
            $record = new MedicalRecord;
            $record->patient()->associate($patient->id);
            $record->admission_date = $faker->dateTimeBetween('-4 months', Carbon::now());
            $record->discharge_date = $faker->dateTimeBetween('-1 months', Carbon::now());
            $record->final_diagnosis = $diagnosis[rand(0,18)];
            $record->record_type = rand(1,2) === 1 ? 'Credited' : 'IRM Deduction';
            $record->total_fee = rand(5000,99999);
            $record->non_medical_fee=($record->total_fee/2);
            $record->pooled_fee=(($record->total_fee/2)*.3);
            $record->total_public_doctors=rand(1,5);
            $record->total_private_doctors=rand(1,5);
            $record->save();
        }

        for($i = 0; $i < rand(100, 1000); $i++)
        {
            $record = new MedicalRecord;
            $record->patient()->associate(rand(1, $patient->count()));
            $record->admission_date = $faker->dateTimeBetween('-4 months', Carbon::now());
            $record->discharge_date = $faker->dateTimeBetween('-1 months', Carbon::now());
            $record->final_diagnosis = $diagnosis[rand(0,18)];
            $record->record_type = rand(1,2) === 1 ? 'Credited' : 'IRM Deduction';
            $record->total_fee = rand(5000,99999);
            $record->non_medical_fee=($record->total_fee/2);
            $record->pooled_fee=(($record->total_fee/2)*.3);
            $record->total_public_doctors=rand(1,5);
            $record->total_private_doctors=rand(1,5);
            $record->save();
        }

        $records = MedicalRecord::get()->all();
        foreach($records as $record)
        {
            $contribution = new Contribution;
            $contribution->contribution = 'admitting';
            $contribution->credit = rand(1000,9999);
            $contribution->status = 'paid';
            $contribution->save();
            $record->personnels()->attach(Personnel::find(rand(0,10)), ['contribution_id' => $contribution->id]);
        }
    }
}
