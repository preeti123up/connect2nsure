<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class DepartmentImport implements ToArray
{

    public static function fields(): array
    {
        return array(
            array('id' => 'department_name','name' =>'Department Name' , 'required' => 'Yes')
        );
    }

    public function array(array $array): array
    {
        return $array;
    }

}
