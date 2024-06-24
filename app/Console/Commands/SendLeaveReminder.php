<?php

namespace App\Console\Commands;

use App\Events\LeaveReminderEvent;
use App\Models\Company;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendLeaveReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-leave-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send leave reminder';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Calculate the date for the next day
        $nextDay = Carbon::now()->addDay()->format('Y-m-d');

        // Get all companies
        $companies = Company::select('id')->get();

        foreach ($companies as $company) {
            $leaves = Leave::with('user.employeeDetail') 
                ->where('company_id', $company->id)
                ->whereDate('leave_date', $nextDay) 
                 ->whereDate('status','pending') 
                ->get();

            foreach ($leaves as $leave) {
                $reportingManager = User::find($leave->user->employeeDetail->reporting_to);
                if ($reportingManager) {
                    $body = 'You have a pending leave request that needs your attention. Employee: ' . $leave->user->name .' Reason: ' . $leave->reason;
                    $title = "Leave Reminder";
                    $fcmToekn = $reportingManager->fcm_token;
                    sendNotificationToUser($body,$title,$fcmToekn, null);
                    event(new LeaveReminderEvent($reportingManager, $leave));
                }
            }
        }
    }
}
