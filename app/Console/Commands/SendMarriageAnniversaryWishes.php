<?php

namespace App\Console\Commands;
use App\Models\Company;
use App\Models\User;
use App\Models\EmployeeDetails;
use Illuminate\Console\Command;
use App\Events\MarriageAnniversaryEvent;

class SendMarriageAnniversaryWishes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-marriage-anniversary-wishes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //

        $currentDay = now()->format('m-d');
        $companies = Company::get();

        // Loop through each company
        foreach ($companies as $company) {
            $todayMarriageAnniversary =  EmployeeDetails::select('employee_details.company_id', 'employee_details.marriage_anniversary_date', 'users.name', 
            'users.image', 'users.id', 'users.email', 'designations.name as designation_name')
            ->join('users', 'employee_details.user_id', '=', 'users.id')
            ->join('designations', 'employee_details.designation_id', '=', 'designations.id')
            ->whereRaw('DATE_FORMAT(`marriage_anniversary_date`, "%m-%d") = ?', [$currentDay])
            ->where('employee_details.company_id', $company->id)
            ->where('users.status', 'active')
            ->orderBy('employee_details.date_of_birth')
            ->get()->toArray();
            

                if(count($todayMarriageAnniversary)>0)
                {
                    event(new MarriageAnniversaryEvent($company, $todayMarriageAnniversary));
                }

        }






    }
}
