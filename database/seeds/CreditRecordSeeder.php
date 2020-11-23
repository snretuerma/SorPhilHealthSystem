<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;
use Faker\Generator as Faker;

use App\Models\CreditRecord;
use App\Models\Doctor;
use App\Models\Hospital;


class CreditRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $physicians=[
            'attending','requesting','surgeon','healthcare','er',
            'anesthesiologist','comanagement','admitting'
        ];
        for($index = 0; $index < 300; $index++) {
            $record = new CreditRecord;
            $record->hospital()->associate(Hospital::find(1)->id);
            $record->patient_name = $faker->name;
            $record->batch = '22112020-28112020';
            $record->admission_date = Carbon::createFromTimeStamp($faker->dateTimeBetween('-360 days', 'now')->getTimestamp());
            $record->discharge_date = Carbon::createFromTimeStamp($faker->dateTimeBetween('now', '+360 days')->getTimestamp());
            $record->type = 'new';
            $record->total = rand(1000, 9999);
            $record->non_medical_fee = $record->total/2;
            $record->medical_fee = $record->non_medical_fee;
            $record->save();
            $doctors = Doctor::inRandomOrder(3)->get();
            foreach($doctors as $doctor) {
                $doctor->credit_records()->attach($record->id, [
                    'doctor_role' => $physicians[rand(0, 7)],
                    'professional_fee' => rand(1000, 9000),
                    'pooled_fee' => rand(100, 999)
                ]);
            }
        }

        for($index = 0; $index < 50; $index++) {
            $record = new CreditRecord;
            $record->hospital()->associate(Hospital::find(1)->id);
            $record->patient_name = $faker->name;
            $record->batch = '22112020-28112020';
            $record->admission_date = Carbon::createFromTimeStamp($faker->dateTimeBetween('-360 days', 'now')->getTimestamp());
            $record->discharge_date = Carbon::createFromTimeStamp($faker->dateTimeBetween('now', '+360 days')->getTimestamp());
            $record->type = 'private';
            $record->total = rand(1000, 9999);
            $record->non_medical_fee = $record->total/2;
            $record->medical_fee = $record->non_medical_fee;
            $record->save();
            $doctors = Doctor::inRandomOrder(3)->get();
            foreach($doctors as $doctor) {
                $doctor->credit_records()->attach($record->id, [
                    'doctor_role' => $physicians[rand(0, 7)],
                    'professional_fee' => rand(1000, 9000),
                    'pooled_fee' => rand(100, 999)
                ]);
            }
        }

        for($index = 0; $index < 100; $index++) {
            $record = new CreditRecord;
            $record->hospital()->associate(Hospital::find(1)->id);
            $record->patient_name = $faker->name;
            $record->batch = '22112020-28112020';
            $record->admission_date = Carbon::createFromTimeStamp($faker->dateTimeBetween('-360 days', 'now')->getTimestamp());
            $record->discharge_date = Carbon::createFromTimeStamp($faker->dateTimeBetween('now', '+360 days')->getTimestamp());
            $record->type = 'old';
            $record->total = rand(1000, 9999);
            $record->non_medical_fee = $record->total/2;
            $record->medical_fee = $record->non_medical_fee;
            $record->save();
        }


    }
}
