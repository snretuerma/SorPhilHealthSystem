<?php

namespace App\Imports;

use App\Models\Budget;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Str;

class BudgetImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private $rows = 0;

    public function model(array $row)
    {
        ++$this->rows;
        
        $start_date = Str::of($row['start_date'])->ltrim()->rtrim();
        $end_date = Str::of($row['end_date'])->ltrim()->rtrim();
        $total = Str::of($row['total'])->ltrim()->rtrim();

        date_default_timezone_set('Asia/Manila');
        $budget = new Budget();
        $budget->hospital_id = Auth::user()->hospital_id;
        $budget->start_date = Carbon::parse($start_date)->format('Y-m-d');
        $budget->end_date = Carbon::parse($end_date)->format('Y-m-d');
        $budget->total = $total;
        $budget->save();
        
    }
    public function getRowCount(): int
    {
        return $this->rows;
    }
}
