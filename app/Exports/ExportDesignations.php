<?php

namespace App\Exports;

use App\Models\Designation;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportDesignations implements FromCollection, WithHeadings, WithTitle,WithStyles
{
    public function headings(): array
    {
        return ['S.No', 'Designation Name'];
    }

    public function collection()
    {
        // Fetch data from your database table
        $data = Designation::select(
            'name'
        )->where(['company_id' => company()->id])->get();

        $i = 1;
        $employeeData = $data->map(function ($item) use (&$i) {
            return [
                $i++,
                $item->name
            ];
        });

        return $employeeData;
    }
    public function title(): string
    {
        return 'Designation Sheet'; // Set the sheet name for this export
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A1:B1' => [
                'font' => [
                    'bold' => true, // Make the headings bold
                    'size' => 14,   // Set the font size (change as needed)
                    'color' => ['rgb' => '000000'], // Set the font color to black
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'FFFF00', // Set the background color (change as needed)
                    ],
                ],
            ],
        ];
    }
}
