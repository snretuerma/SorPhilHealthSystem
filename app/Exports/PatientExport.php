<?php

namespace App\Exports;
use Auth;
use App\Models\Patient;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PatientExport implements FromQuery, WithHeadings, WithColumnWidths, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow(); // e.g. 10
        $highestColumn = $sheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
        for ($row = 1; $row <= $highestRow; ++$row) {
            for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                $styleArray =[
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];
                $sheet->getStyle('A'.$row, 'A'.$row)->applyFromArray($styleArray);
                $sheet->getStyle('B'.$row, 'B'.$row)->applyFromArray($styleArray);
                $sheet->getStyle('C'.$row, 'C'.$row)->applyFromArray($styleArray);
                $sheet->getStyle('D'.$row, 'D'.$row)->applyFromArray($styleArray);
                $sheet->getStyle('E'.$row, 'E'.$row)->applyFromArray($styleArray);
                $sheet->getStyle('F'.$row, 'F'.$row)->applyFromArray($styleArray);
                $sheet->getStyle('G'.$row, 'G'.$row)->applyFromArray($styleArray);
                $sheet->getStyle('H'.$row, 'H'.$row)->applyFromArray($styleArray);
            }
        } 
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startColor' => [
                        'argb' => 'FFA0A0A0',
                    ],
                    'endColor' => [
                        'argb' => 'FFFFFFFF',
                    ],
                ],
            ],
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15, 
            'C' => 20, 
            'D' => 20, 
            'E' => 20, 
            'F' => 20, 
            'G' => 20, 
            'H' => 20,           
        ];
    }
    public function headings(): array
    {
        return [
            'first_name', 
            'middle_name', 
            'last_name',
            'name_suffix',
            'sex',
            'birthdate',
            'marital_status',
            'philhealth_number',
        ];
    }
    public function query()
    {
        $patient = Patient::query();
        $patient->select('first_name', 
                         'middle_name', 
                         'last_name',
                         'name_suffix',
                          Patient::raw('CASE WHEN `sex` = "0" THEN "Male"
                                             WHEN `sex` = "1" THEN "Female" 
                                        END'),
                         'birthdate',
                          Patient::raw('CASE WHEN `marital_status` = "0" THEN "Single"
                                             WHEN `marital_status` = "1" THEN "Married"
                                             WHEN `marital_status` = "2" THEN "Divorced"
                                             WHEN `marital_status` = "3" THEN "Widowed"
                                             WHEN `marital_status` > "3" THEN "Others/Prepare Not to Say" 
                                        END'),
                         'philhealth_number')
                ->where('hospital_id', Auth::user()->hospital_id);
        return $patient;
    }
}
