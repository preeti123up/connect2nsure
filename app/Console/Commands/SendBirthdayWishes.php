<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\User;
use App\Models\EmployeeDetails;
use Illuminate\Console\Command;
use App\Events\BirthdayWishesEvent;

class SendBirthdayWishes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthday-wishes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send birthday wish to employee';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDay = now()->format('m-d');
        $companies = Company::get();
    
        foreach ($companies as $company) {
            $query = EmployeeDetails::select('employee_details.company_id', 'employee_details.date_of_birth', 'users.name', 
            'users.image', 'users.id', 'users.email', 'designations.name as designation_name')
            ->join('users', 'employee_details.user_id', '=', 'users.id')
            ->join('designations', 'employee_details.designation_id', '=', 'designations.id')
            ->whereRaw('DATE_FORMAT(`date_of_birth`, "%m-%d") = ?', [$currentDay])
            ->where('employee_details.company_id', $company->id)
            ->where('users.status', 'active')
            ->orderBy('employee_details.date_of_birth');
        $todayBirthdayWish = $query->get()->toArray();
            if (count($todayBirthdayWish) > 0) {
                event(new BirthdayWishesEvent($company, $todayBirthdayWish));
            }
        }
        //  $user = User::with('company')->get()->toArray();
        //  $company = Company::first();
       
        // foreach($user as $i => $v){
        //     $companyLogo  = asset('user-uploads/app-logo/'.$company->logo);
        //     $title = "Happy new Year " . $user[$i]['name'];
        //     $body = "Happy New Year! Team Sileo. 🌟 Success and joy await in 2024! 🎉 ";
        //     if($user[$i]['fcm_token'] !== null){
        //         sendNotificationToUser($body, $title, $user[$i]['fcm_token'],$companyLogo);
        //     }
          
        // }
    }
    }
    
