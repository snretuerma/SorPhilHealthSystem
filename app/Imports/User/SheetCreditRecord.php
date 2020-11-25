<?php

namespace App\Imports\User;

use App\Models\CreditRecord;
use Carbon\Carbon;
use Auth;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SheetCreditRecord implements ToCollection
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Manila');
    }
    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            $date_properties = \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
            return $date_properties->format('Y-m-d');
        } catch (\ErrorException $e) {
            //return \Carbon\Carbon::createFromFormat($format, $value);
            return Carbon::parse($value)->format('Y-m-d');
        }
    }
    public function collection(Collection $rows)
    {
       // dd($this->transformDate($rows[1][1]));
        //dd($rows[1][1]);
        $batch = "01";
        $i=0;
        foreach ($rows as $row) 
        { 
            // dd($rows[1]);
            if($i > 0){
                //dd($row[1]);
                //dd($rows[$i][0]);
                /*if($rows[1] > 0){*/
                $type = "new";
                    if($row[13] == 0 && Carbon::parse($this->transformDate($row[1]))->format('Ymd') < "20200301"){ 
                        $type = "old"; 
                    }elseif($row[13] == 1){ 
                        $type = "private";
                    }
                    $non_medical = ($row[3] / 2);
                    $medical_fee = ($row[3] / 2);

                    //dd($type);
                   // dd(Carbon::parse($this->transformDate($row[1]))->format('Ymd')."--"."20200301");

                  // dd($row[0]);

                    //dd($non_medical."--".$medical_fee."--".$type."--".$row[13]."--".Carbon::parse($row[1])->format('Y-m-d')."|".$row[1]."|"."--20200301");
//dd(Carbon::parse($this->transformDate($row[2]))->format('Y-m-d'));
                   $gg = [
                    'hospital_id' => Auth::user()->hospital_id,
                    'patient_name' => $row[0],
                    'batch' => $batch,
                    'admission_date' => Carbon::parse($this->transformDate($row[1]))->format('Y-m-d'),
                    'discharge_date' => Carbon::parse($this->transformDate($row[2]))->format('Y-m-d'),
                    'type' => $type,
                    'total' => $row[3],
                    'non_medical' => $non_medical,
                    'medical_fee' => $medical_fee,
                   ];
                   CreditRecord::create($gg)->save();
                   //dd($gg);
                    /*$record = CreditRecord::create([
                        'hospital_id' => Auth::user()->hospital_id,
                        'patient_name' => $row[0],
                        'batch' => $batch,
                        'admission_date' => Carbon::parse($this->transformDate($row[1]))->format('Y-m-d'),
                        'discharge_date' => Carbon::parse($this->transformDate($row[2]))->format('Y-m-d'),
                        'type' => $type,
                        'total' => $row[3],
                        'non_medical' => $non_medical,
                        'medical_fee' => $medical_fee,
                    ]);
                    $record->save();*/
                /*}*/
            }
        $i++;}

       // dd($rows);
       /*$da = [];
       foreach ($rows as $row) 
        {
            array_push($da, $row[0]);
        }
        if($da){
            dd($da);
        }else{
            dd("sheet not found");
        }*/
        
        

        /*foreach ($rows as $row) 
        {
            User::create([
                'name' => $row[0],
            ]);
        }*/
    }
}

?>