<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Hospital;
use App\Models\CreditRecord;
use App\Models\Doctor;
use App\Models\PooledRecord;
use Carbon\Carbon;

use Illuminate\Http\Request;

class ProcessCreditRecord implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $requestData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
       // $this->middleware('auth');
        $this->requestData = $request;
    }

    /**
     * Split data with two comma
     *
     * @var String $data
     * @return Array
     */
    public function splitTwoComma(String $data)
    {
        if (str_replace(' ', '', strtoupper(trim($data))) == "NULL" || $data == "" || $data == null) {
            return null;
        } else {
            $all_match = [];
            preg_match_all('/[^,]+,[^,]+/', $data, $all_match);
            if (count($all_match[0]) > 0) {
                return $all_match;
            } else {
                return null;
            }
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $request = $this->requestData;
        $hospital_id = $request['hospital_id'];

        $hospital = Hospital::find($hospital_id);
        $setting = $hospital->setting;
        $doctor_list_complete = $request[0]['doctor_list'];
        $cell_physician = [
            'Attending_Physician',
            'Admitting_Physician',
            'Requesting_Physician',
            'Co_Management',
            'Anesthesiologist_Physician',
            'Surgeon_Physician',
            'HealthCare_Physician',
            'ER_Physician'
        ];
        $physician_percentage = array(
            'Admitting_Physician' => json_decode($setting)->physicians[6],
            'Requesting_Physician' => json_decode($setting)->physicians[0],
            'Co_Management' => json_decode($setting)->physicians[5],
            'Anesthesiologist_Physician' => json_decode($setting)->physicians[4],
            'Surgeon_Physician' => json_decode($setting)->physicians[1],
            'HealthCare_Physician' => json_decode($setting)->physicians[2],
            'ER_Physician' => json_decode($setting)->physicians[3]
        );
        for ($i = 0; $i < count($request[0]['import_batch']); $i++) {
            $batch = $request[0]['import_batch'][$i];
            $acpn = $request[0]['doctor_record'][$i]['content'];
            foreach ($acpn as $each) {
                $doctor_ids = [];
                $doctor_as = [];
                $pf = [];
                $receive_by_non_attending = 0;
                $seventy_percent = ($each['Total_PF'] * json_decode($setting)->nonmedical) *
                    json_decode($setting)->shared;
                foreach ($cell_physician as $physician) {
                    if ($this->splitTwoComma($each[$physician]) != null) {
                        foreach ($this->splitTwoComma($each[$physician])[0] as $name) {
                            foreach ($doctor_list_complete as $doctor_info) {
                                if (str_replace(' ', '', strtolower(trim($doctor_info['name']))) ==
                                    str_replace(' ', '', strtolower(trim($name)))
                                ) {
                                    array_push($doctor_ids, $doctor_info['id']);
                                    array_push($doctor_as, $physician);
                                    if ($physician == "Attending_Physician") {
                                        array_push($pf, $seventy_percent);
                                    } else {
                                        array_push(
                                            $pf,
                                            ($seventy_percent * $physician_percentage[$physician])
                                        );
                                        $receive_by_non_attending +=
                                            ($seventy_percent * $physician_percentage[$physician]);
                                    }
                                }
                            }
                        }
                    }
                }
                $record = new CreditRecord;
                $record->hospital()->associate($hospital_id);
                $record->patient_name = $each['Patient_Name'];
                $record->batch = $batch;
                $record->admission_date = Carbon::parse($each['Admission_Date'])
                    ->setTimeZone('Asia/Manila')
                    ->format('Y-m-d h:i:s');
                $record->discharge_date = Carbon::parse($each['Discharge_Date'])
                    ->setTimeZone('Asia/Manila')
                    ->format('Y-m-d h:i:s');
                $doctors = Doctor::where('hospital_id', $record->hospital_id)
                    ->whereIn('id', $doctor_ids)
                    ->get();
                if ($each['Is_Private'] == "1") {
                    $record->record_type = 'private';
                    $record->total = $each['Total_PF'];
                    $record->non_medical_fee = 0;
                    $record->medical_fee = 0;
                    $record->save();
                    foreach ($doctors as $doctor) {
                        $doctor->credit_records()->attach($record->id, [
                            'doctor_role' => ($doctor_as[array_search($doctor->id, $doctor_ids)] ==
                                'Co_Management') ? 'comanagement' :
                                explode(
                                    '_',
                                    strtolower($doctor_as[array_search($doctor->id, $doctor_ids)])
                                )[0],
                            'professional_fee' => $record->total,
                        ]);
                    }
                } else {
                    if (Carbon::parse($each['Admission_Date'])
                        ->setTimeZone('Asia/Manila')
                        ->format('Y-m-d h:i:s') < "2020-03-01"
                    ) {
                        $record->record_type = 'old';
                        $record->total = $each['Total_PF'];
                        $record->non_medical_fee = $record->total * json_decode($setting)->nonmedical;
                        $record->medical_fee = $record->non_medical_fee;
                        $record->save();
                        foreach ($doctors as $doctor) {
                            $doctor->credit_records()->attach($record->id, [
                                'doctor_role' => ($doctor_as[array_search($doctor->id, $doctor_ids)] ==
                                    'Co_Management') ? 'comanagement' :
                                    explode(
                                        '_',
                                        strtolower($doctor_as[array_search($doctor->id, $doctor_ids)])
                                    )[0],
                                'professional_fee' => ($record->non_medical_fee / $doctor->count())
                            ]);
                        }
                    } else {
                        $record->record_type = 'new';
                        $record->total = $each['Total_PF'];
                        $record->non_medical_fee = $record->total * json_decode($setting)->nonmedical;
                        $record->medical_fee = $record->total * json_decode($setting)->medical;
                        $record->save();
                        $full_time_doctors = Doctor::select('id')
                            ->where('is_active', true)
                            ->where('is_parttime', false)
                            ->pluck('id')
                            ->toArray();
                        $part_time_doctors = Doctor::select('id')
                            ->where('is_active', true)
                            ->where('is_parttime', true)
                            ->pluck('id')
                            ->toArray();
                        $pooled_record = new PooledRecord;
                        $pooled_record->full_time_doctors = json_encode($full_time_doctors);
                        $pooled_record->part_time_doctors = json_encode($part_time_doctors);
                        $total_pooled_fee = $record->non_medical_fee * json_decode($setting)->pooled;
                        $initial_individual_fee = ($record->non_medical_fee * json_decode($setting)->pooled) /
                            (count($full_time_doctors) + (count($part_time_doctors) / 2));
                        $pooled_record->full_time_individual_fee = $initial_individual_fee;
                        $pooled_record->part_time_individual_fee = $initial_individual_fee / 2;
                        $pooled_record->record_id = $record->id;
                        $pooled_record->save();
                        foreach ($doctors as $doctor) {
                            $computed_pf = 0;
                            if ($doctor_as[array_search($doctor->id, $doctor_ids)] == 'Attending_Physician') {
                                $computed_pf = ($pf[array_search($doctor->id, $doctor_ids)] -
                                    $receive_by_non_attending) /
                                    array_count_values($doctor_as)[$doctor_as[array_search($doctor->id, $doctor_ids)]];
                            } else {
                                $computed_pf = $pf[array_search($doctor->id, $doctor_ids)];
                            }
                            $doctor->credit_records()->attach($record->id, [
                                'doctor_role' => ($doctor_as[array_search($doctor->id, $doctor_ids)] ==
                                    'Co_Management') ? 'comanagement' :
                                    explode(
                                        '_',
                                        strtolower($doctor_as[array_search($doctor->id, $doctor_ids)])
                                    )[0],
                                'professional_fee' => $computed_pf
                            ]);
                        }
                    }
                }
            }
        }
    }
}
