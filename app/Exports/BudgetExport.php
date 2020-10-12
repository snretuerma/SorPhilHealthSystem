<?php

namespace App\Exports;

use Auth;
use App\Models\Budget;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class BudgetExport implements FromQuery, WithHeadings, WithColumnWidths, WithStyles
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
        ];
    }
    public function headings(): array
    {
        return [
            'start_date', 
            'end_date',
            'total', 
        ];
    }
    
    public function query()
    {
        $budget = Budget::query();
        $budget->select('start_date',
                         'end_date',
                         'total')
                ->where('hospital_id', Auth::user()->hospital_id);
        return $budget;
    }
}
