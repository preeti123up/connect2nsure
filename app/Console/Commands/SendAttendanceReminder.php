<?php

namespace App\Console\Commands;

use App\Events\AttendanceReminderEvent;
use App\Models\AttendanceSetting;
use App\Models\Company;
use App\Models\Holiday;
use App\Models\User;
use App\Models\EmployeeShiftSchedule;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendAttendanceReminder extends Command
{
    protected $signature = 'send-attendance-reminder';
    protected $description = 'Send attendance reminder to the employee';

    public function handle()
    {
        $today = now()->format('Y-m-d');
        $companies = Company::select('id')->get();

        foreach ($companies as $company) {
            $attendanceSetting = AttendanceSetting::where('company_id', $company->id)->first();

            if ($attendanceSetting && $this->shouldSendReminder($attendanceSetting, $today)) {
                $currentDateTime = now()->setTimezone('Asia/Kolkata');
                if ($attendanceSetting->alert_after_status == 1 && !is_null($attendanceSetting->alert_after) && $attendanceSetting->alert_after != 0) {
                    $currentDateTime = $currentDateTime->subMinutes($attendanceSetting->alert_after);
                }

                $shiftSetting = $attendanceSetting->shift;
                $this->sendClockInReminders($company->id, $today, $currentDateTime, $shiftSetting);
                $this->sendClockOutReminders($company->id, $today, $currentDateTime, $shiftSetting);
            }
        }
    }

    private function shouldSendReminder($attendanceSetting, $today)
    {
        if ($attendanceSetting->alert_after_status != 1 || is_null($attendanceSetting->alert_after) || $attendanceSetting->alert_after == 0) {
            return false;
        }

        $holiday = Holiday::where('company_id', $attendanceSetting->company_id)
            ->where('date', $today)
            ->where('holiday_type', 'Gazetted')
            ->first();

        return !$holiday;
    }

    private function sendClockInReminders($companyId, $today, $currentDateTime, $shiftSetting)
    {
        $usersData = $this->getUsersForReminder($companyId, 'attendance_reminder', 'clock_in_time', $today);

        foreach ($usersData as $userData) {
            $userShift = $this->getUserShift($userData->id, $shiftSetting);
            $startDateTime = Carbon::parse($today . ' ' . $userShift->office_start_time, 'Asia/Kolkata');

            if ($currentDateTime->greaterThan($startDateTime) && $this->isOfficeOpen($today, $userShift->office_open_days)) {
                $this->sendNotification($userData, $today, "Don't forget to punch in at the start of your shift for accurate attendance records. ⏰", 'attendance_reminder');
            }
        }
    }

    private function sendClockOutReminders($companyId, $today, $currentDateTime, $shiftSetting)
    {
        $usersData = $this->getUsersForReminder($companyId, 'attendance_out_reminder', 'clock_out_time', $today);

        foreach ($usersData as $userData) {
            $userShift = $this->getUserShift($userData->id, $shiftSetting);
            $endDateTime = Carbon::parse($today . ' ' . $userShift->office_end_time, 'Asia/Kolkata');

            if ($currentDateTime->greaterThan($endDateTime) && $this->isOfficeOpen($today, $userShift->office_open_days)) {
                $this->sendNotification($userData, $today, "Don't forget to punch out at the end of your shift for accurate attendance records. ⏰", 'attendance_out_reminder');
            }
        }
    }

    private function getUsersForReminder($companyId, $reminderColumn, $timeColumn, $today)
    {
        return User::with('employeeDetail')
            ->leftJoin('attendances', function ($join) use ($today, $timeColumn) {
                $join->on('users.id', '=', 'attendances.user_id')
                    ->where(DB::raw('DATE(attendances.' . $timeColumn . ')'), '=', $today);
            })
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->join('employee_details', 'employee_details.user_id', '=', 'users.id')
            ->onlyEmployee()
            ->where('users.company_id', $companyId)
            ->whereNull('attendances.' . $timeColumn)
            ->where(function ($query) use ($today, $reminderColumn) {
                $query->where('employee_details.' . $reminderColumn, '!=', $today)
                    ->orWhereNull('employee_details.' . $reminderColumn);
            })
            ->select('users.id', 'users.name', 'users.fcm_token', 'employee_details.' . $reminderColumn, 'attendances.id as attendance_id')
            ->groupBy('users.id')
            ->get();
    }

    private function getUserShift($userId, $defaultShift)
    {
        $todayShift = EmployeeShiftSchedule::where('user_id', $userId)
            ->whereDate('date', today()->format('Y-m-d'))
            ->first();
        return $todayShift ? $todayShift->shift : $defaultShift;
    }

    private function isOfficeOpen($date, $officeOpenDays)
    {
        $dayOfWeek = Carbon::parse($date, 'Asia/Kolkata')->format('N');
        return in_array($dayOfWeek, $officeOpenDays);
    }

    private function sendNotification($userData, $today, $message, $reminderColumn)
    {
        $title = "Hello " . $userData->name . "!";
        if ($userData->fcm_token) {
            $this->sendNotificationToUser($message, $title, $userData->fcm_token);
            $userData->employeeDetail->$reminderColumn = $today;
            $userData->employeeDetail->save();
        }
    }

    private function sendNotificationToUser($body, $title, $fcmToken)
    {
        // Assuming you have a function or service to send the notification
        sendNotificationToUser($body, $title, $fcmToken, null);
    }
}
