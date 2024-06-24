<?php

namespace App\Exports;
use App\Exports\EmployeeBulkFormate;
use App\Exports\ExportDesignations;
use App\Exports\ExportDepartments;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EmployeeMultiSheetExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        return [
            'Sheet 1' => new EmployeeBulkFormate(),
            'Sheet 2' => new ExportDesignations(),
            'Sheet 3' => new ExportDepartments()
        ];
    }
}


?>
