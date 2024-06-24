<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class EmployeeImport implements ToArray
{

    public static function fields(): array
    {
        return array(
            array('id' => 'employee_id', 'name' => __('modules.employees.employeeId'), 'required' => 'Yes'),
            array('id' => 'name', 'name' => __('modules.employees.employeeName'), 'required' => 'Yes',),
            array('id' => 'email', 'name' => __('modules.employees.employeeEmail'), 'required' => 'Yes',),
            array('id' => 'mobile', 'name' => __('app.mobile'), 'required' => 'No',),
            array('id' => 'gender', 'name' => __('modules.employees.gender'), 'required' => 'No',),
            array('id' => 'date_of_birth', 'name' => __('DOB'), 'required' => 'No',),
            array('id' => 'joining_date', 'name' => __('modules.employees.joiningDate'), 'required' => 'Yes'),
            array('id' => 'reporting_to', 'name' => __('Reporting To'), 'required' => 'no'),
            array('id' => 'designation_name', 'name' => __('Designation Name'), 'required' => 'Yes'),
            array('id' => 'department_name', 'name' => __('Department Name'), 'required' => 'Yes'),
        );
    }

    public function array(array $array): array
    {
        return $array;
    }

}
