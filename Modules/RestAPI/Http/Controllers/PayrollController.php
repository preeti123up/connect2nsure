<?php

namespace Modules\RestAPI\Http\Controllers;
use Modules\RestAPI\Entities\Leave;
use App\Models\EmployeeDetails;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Helper\Reply;
use Froiden\RestAPI\ApiResponse;
use Modules\Payroll\Entities\SalarySlip;
use Modules\Payroll\Entities\PayrollCycle;
use Modules\Payroll\Entities\PayrollSetting;


class PayrollController extends ApiBaseController
{
    public function payslip(Request $request)
{ 
    $salarySlip = SalarySlip::with('salary_group', 'salary_payment_method', 'payroll_cycle')
        ->where('user_id', api_user()->id)
        ->where('month', $request->month)
        ->first();

    if ($salarySlip !== NULL) {
        $salarySlipArray = $salarySlip->toArray(); 
        $salaryJson = json_decode($salarySlip->salary_json, true);
        $salarySlipArray['earnings'] = $salaryJson['earnings'] ?? [];
        $salarySlipArray['deductions'] = $salaryJson['deductions'] ?? [];
        $salarySlipArray['extraJson'] = json_decode($salarySlip->extra_json, true) ?? [];
        $pdfUrl = url('/payroll/download/' . md5($salarySlip->id));
        $salarySlipArray['pdf_url'] = $pdfUrl;

        if($salarySlip->payroll_cycle->cycle == 'monthly'){
            $basicSalary = (float)$salarySlip->basic_salary;
        }
        elseif($salarySlip->payroll_cycle->cycle == 'weekly'){
            $basicSalary = ((float)$salarySlip->basic_salary / 4);
        }
        elseif($salarySlip->payroll_cycle->cycle == 'semimonthly'){
            $salarySlip = ((float)$salarySlip->basic_salary / 2);
        }
        elseif($salarySlip->payroll_cycle->cycle == 'biweekly'){
            $perday = ((float)$salarySlip->basic_salary / 30);
            $basicSalary = $perday * 14;
        }

        $earn = [];
        foreach($salarySlipArray['earnings'] as $key => $value){
            if($key != 'Total Hours')
            {
                $earn[] = $value;
            }
        }
        $earn = array_sum($earn);
        $fixedAllowance = $salarySlip->gross_salary - ($basicSalary + $earn);
        if ($fixedAllowance < 0){
            $fixedAllowance = 0;
        }
        if($fixedAllowance < 1 && $fixedAllowance > -1 ){
            $fixedAllowance = 0;
        }
        $fixedAllow = ($salarySlip->fixed_allowance > 0) ? $salarySlip->fixed_allowance : $fixedAllowance;
        $salarySlipArray['fixedAllow'] = $fixedAllow;

        return ApiResponse::make('Data Found successfully', [$salarySlipArray]);
    } else {
        return ApiResponse::make('Data Not Found', []);
    }
}
}
