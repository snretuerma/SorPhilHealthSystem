<?php

namespace App\Imports\User;

use App\Imports\User\SheetCreditRecord;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;

class CreditRecordImport implements WithMultipleSheets, SkipsUnknownSheets
{
   
    public function sheets(): array
    {
        /*return [
            0 => new FirstSheetImport(),
            1 => new SecondSheetImport(),
        ];*/
        //dd([0=>new CreditRecord()]);
        return [
            0 => new SheetCreditRecord(),
            1 => new SheetCreditRecord(),
            2 => new SheetCreditRecord(),
            3 => new SheetCreditRecord(),
            4 => new SheetCreditRecord(),
            5 => new SheetCreditRecord(),
            6 => new SheetCreditRecord(),
            7 => new SheetCreditRecord(),
            8 => new SheetCreditRecord(),
            9 => new SheetCreditRecord(),
            10 => new SheetCreditRecord(),
            11 => new SheetCreditRecord(),
            12 => new SheetCreditRecord(),
            13 => new SheetCreditRecord(),
            14 => new SheetCreditRecord(),
            15 => new SheetCreditRecord(),
            16 => new SheetCreditRecord(),
            17 => new SheetCreditRecord(),
            18 => new SheetCreditRecord(),
            19 => new SheetCreditRecord(),
        ];

        //dd($this->getSheet());
        

        

       


    } 
    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        //info("Sheet {$sheetName} was skipped");
        dd(("Sheet {$sheetName} was skipped"));
    }
    
    

}

?>