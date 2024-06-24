<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeeBulkFormate implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    public function headings(): array
    {
        return ['S.No','Employee Id', 'Name', 'Email','Mobile' ,'Gender','Date of Birth','Joining Date', 'Reporting To','Designation Name', 'Department Name'];
    }

    public function collection()
    {

        $data = [
            [1, 'EMP-001', 'John Doe', 'john.doe@example.com', '9876543210', 'Male','2023-12-10','2023-01-02','Admin', 'Manager', 'IT'],
        ];

        foreach ($data as &$row) {

            $row[6] = date('Y-m-d', strtotime($row[6]));
            $row[7] = date('Y-m-d', strtotime($row[7]));        }

        return collect($data);
    }

    public function title(): string
    {
        return 'Employee Sheet';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A1:K1' => [
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
