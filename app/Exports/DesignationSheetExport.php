<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DesignationSheetExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    public function headings(): array
    {
        return ['S.No','Designation Name'];
    }

    public function collection()
    {
        return collect(); // Empty collection, no data
    }

    public function title(): string
    {
        return 'Designation Sheet';
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
?>
