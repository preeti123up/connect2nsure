<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class DesignationImport implements ToArray
{

    public static function fields(): array
    {
        return array(
            array('id' => 'designation_name','name' =>'Designation Name' , 'required' => 'Yes')
        );
    }

    public function array(array $array): array
    {
        return $array;
    }

};
