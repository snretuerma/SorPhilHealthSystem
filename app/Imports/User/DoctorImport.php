<?php

namespace App\Imports\User;

use App\Models\Doctor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Str;

class DoctorImport implements ToModel,WithHeadingRow
{
    private $rows = 0;

    public function model(array $row)
    {
        ++$this->rows;
         /*foreach($row as $v){
        dd(strval($row['name']));
        }*/
       $name = Str::of($row['name'])->ltrim()->rtrim();
        $is_active = Str::of($row['is_active'])->ltrim()->rtrim();
        $is_parttime = Str::of($row['is_parttime'])->ltrim()->rtrim();

        date_default_timezone_set('Asia/Manila');
        $doctor = new Doctor();
        $doctor->hospital_id = Auth::user()->hospital_id;
        $doctor->name = $name;
        $doctor->is_active = $is_active;
        $doctor->is_parttime = $is_parttime;
        $doctor->save();
    }
    public function getRowCount(): int
    {
        return $this->rows;
    }
}
