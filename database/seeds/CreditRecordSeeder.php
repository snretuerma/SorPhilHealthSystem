<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;
use Faker\Generator as Faker;

use App\Models\CreditRecord;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\PooledRecord;

class CreditRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for($index = 0; $index < 5; $index++) {
            $record = new CreditRecord;
            $record->hospital()->associate(Hospital::find(1)->id);
            $record->patient_name = $faker->name;
            $record->batch = '22112020-28112020';
            $record->admission_date = Carbon::createFromTimeStamp($faker->dateTimeBetween('-360 days', 'now')->getTimestamp());
            $record->discharge_date = Carbon::createFromTimeStamp($faker->dateTimeBetween('now', '+360 days')->getTimestamp());
            $record->record_type = 'new';
            $record->total = rand(1000, 9999);
            $record->non_medical_fee = $record->total/2;
            $record->medical_fee = $record->non_medical_fee;
            $record->save();
            $doctors = Doctor::where('hospital_id', $record->hospital_id)->get()->random(3);
            $full_time_doctors = Doctor::select('id')
                ->where('is_active', true)
                ->where('is_parttime', false)
                ->pluck('id')
                ->toArray();
            $part_time_doctors = Doctor::select('id')->where('is_active', true)->where('is_parttime', true)->pluck('id')->toArray();
            $pooled_record = new PooledRecord;
            $pooled_record->full_time_doctors = json_encode($full_time_doctors);
            $pooled_record->part_time_doctors = json_encode($part_time_doctors);
            $total_pooled_fee = $record->non_medical_fee*0.3;
            $initial_individual_fee = ($record->non_medical_fee*0.3)/(count($full_time_doctors)+(count($part_time_doctors)/2));

            $pooled_record->full_time_individual_fee = $initial_individual_fee;
            $pooled_record->part_time_individual_fee = $initial_individual_fee/2;
            $pooled_record->record_id = $record->id;
            $pooled_record->save();
            foreach($doctors as $doctor) {
                $doctor->credit_records()->attach($record->id, [
                    'doctor_role' => 'attending',
                    'professional_fee' => ($record->non_medical_fee*0.7)/$doctor->count()
                ]);
            }
        }

        for($index = 0; $index < 5; $index++) {
            $record = new CreditRecord;
            $record->hospital()->associate(Hospital::find(1)->id);
            $record->patient_name = $faker->name;
            $record->batch = '22112020-28112020';
            $record->admission_date = Carbon::createFromTimeStamp($faker->dateTimeBetween('-360 days', 'now')->getTimestamp());
            $record->discharge_date = Carbon::createFromTimeStamp($faker->dateTimeBetween('now', '+360 days')->getTimestamp());
            $record->record_type = 'private';
            $record->total = rand(1000, 9999);
            $record->non_medical_fee = 0;
            $record->medical_fee = 0;
            $record->save();
            $doctors = Doctor::where('hospital_id', $record->hospital_id)->get()->random(3);
            foreach($doctors as $doctor) {
                $doctor->credit_records()->attach($record->id, [
                    'doctor_role' => 'attending',
                    'professional_fee' => $record->total,
                ]);
            }
        }

        for($index = 0; $index < 5; $index++) {
            $record = new CreditRecord;
            $record->hospital()->associate(Hospital::find(1)->id);
            $record->patient_name = $faker->name;
            $record->batch = '22112020-28112020';
            $record->admission_date = Carbon::createFromTimeStamp($faker->dateTimeBetween('-360 days', 'now')->getTimestamp());
            $record->discharge_date = Carbon::createFromTimeStamp($faker->dateTimeBetween('now', '+360 days')->getTimestamp());
            $record->record_type = 'old';
            $record->total = rand(1000, 9999);
            $record->non_medical_fee = $record->total/2;
            $record->medical_fee = $record->non_medical_fee;
            $record->save();
        }


    }
}
