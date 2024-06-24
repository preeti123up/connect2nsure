<?php

namespace App\Listeners;

use App\Events\LeaveEvent;
use App\Models\EmployeeDetails;
use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\Role;
use App\Notifications\LeaveApplication;
use App\Notifications\LeaveStatusApprove;
use App\Notifications\LeaveStatusReject;
use App\Notifications\LeaveStatusUpdate;
use App\Notifications\MultipleLeaveApplication;
use App\Notifications\NewLeaveRequest;
use App\Notifications\NewMultipleLeaveRequest;
use App\Models\User;
use DateTime;
use App\Models\UserPermission;
use Illuminate\Support\Facades\Notification;

class LeaveListener
{

    /**
     * Handle the event.
     *
     * @param LeaveEvent $event
     * @return void
     */

    public function handle(LeaveEvent $event)
    {
        $leaveApproveRejectPermission = Permission::where('name', 'approve_or_reject_leaves')->first();
        $permissionUserIds = UserPermission::where('permission_id', $leaveApproveRejectPermission->id)->where('permission_type_id', PermissionType::ALL)->get()->pluck('user_id')->toArray();

        $reportingTo = EmployeeDetails::where('user_id', user()->id)->pluck('reporting_to')->toArray();

        $adminUserIds = User::allAdmins($event->leave->company->id)->pluck('id')->toArray();

        $adminUserIds = array_merge($permissionUserIds, $adminUserIds);
     



        if ($reportingTo == null) {
            $adminUsers = User::whereIn('id', $adminUserIds)->get();
        }
        else {
            $notificationTo = array_merge($reportingTo, $adminUserIds);
            
            $adminUsers = User::whereIn('id', $notificationTo)->distinct('id')->get();
          
            
            
        }

        if ($event->status == 'created') {

            $title = "Dear ".$event->leave->user->name;
            $admin_title = "Leave Application Applied From ".$event->leave->user->name;
            if (!is_null($event->multiDates)) {
     Notification::send($event->leave->user, new MultipleLeaveApplication($event->leave, $event->multiDates));
    Notification::send($adminUsers, new NewMultipleLeaveRequest($event->leave, $event->multiDates));
    $dateArray = explode(',', $event->multiDates);
    $lastDate = end($dateArray);
$firstDateTime = new DateTime($dateArray[0]);
$lastDateTime = new DateTime($lastDate);
$formattedFirstDate = $firstDateTime->format("d M");
$formattedLastDate = $lastDateTime->format("d M");
$body = "Leave application has been successfully submitted for the Date From: " . $formattedFirstDate . " To: " . $formattedLastDate;
$admin_body = "Leave Date From: " . $formattedFirstDate . ", To Date: " . $formattedLastDate . " Reason for absence: " . $event->leave->reason . " Leave Type: " . $event->leave->type->type_name;

}
 else {
                 $body =  "Leave application has been successfully submitted for the following date(s): ".$event->leave->leave_date->format("d M");
                 $admin_body =  "Leave Date: ".$event->leave->leave_date->format("d M")." Reason for absence: ".$event->leave->reason." Leave Type: ".$event->leave->type->type_name;
                 Notification::send($event->leave->user, new LeaveApplication($event->leave));
                 Notification::send($adminUsers, new NewLeaveRequest($event->leave));
            }
            // send firebase notifications to user
            sendNotificationToUser($body, $title, $event->leave->user->fcm_token,"leave.png");
             // send firebase notifications to admin
             foreach($adminUsers as $i => $v){
                 sendNotificationToUser(nl2br($admin_body), $admin_title,$v->fcm_token,"leave.png");  
             }
        
            
        }
        elseif ($event->status == 'statusUpdated') {
            if ($event->leave->status == 'approved') {
                Notification::send($event->leave->user, new LeaveStatusApprove($event->leave));
            }
            else {
                Notification::send($event->leave->user, new LeaveStatusReject($event->leave));
            }
               $title = "Dear ".$event->leave->user->name;
               $body =  "Leave application has been : ".$event->leave->status;
               sendNotificationToUser($body, $title, $event->leave->user->fcm_token,"leave.png");
             
        }
        elseif ($event->status == 'updated') {
            Notification::send($event->leave->user, new LeaveStatusUpdate($event->leave));
        }
    }

}
