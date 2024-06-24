<?php
namespace Modules\RestAPI\Http\Controllers;
use Modules\RestAPI\Entities\Leave;
use Modules\RestAPI\Http\Requests\Leave\CreateRequest;
use App\Models\EmployeeDetails;
use App\Models\EmployeeLeaveQuota;
use App\Models\AttendanceSetting;
use App\Models\Holiday;
use App\Models\LeaveFile;
use App\Models\LeaveSetting;
use App\Models\LeaveType;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Helper\Reply;
use Froiden\RestAPI\ApiResponse;
use App\Helper\Files;
use App\Http\Requests\Leaves\ActionLeave;
use Illuminate\Support\Facades\Log;
use App\Models\Attendance;



class LeaveController extends ApiBaseController
{
    protected $model = Leave::class;
    protected $storeRequest = CreateRequest::class;

public function apply(CreateRequest $request)
{
        $this->user = api_user();
        $sendwhich = false;
        $leaveType = LeaveType::findOrFail($request->leave_type_id);
        $employeeLeaveQuota = EmployeeLeaveQuota::whereUserId($this->user->id)->whereLeaveTypeId($request->leave_type_id)->first();

        $totalAllowedLeaves = ($employeeLeaveQuota) ? $employeeLeaveQuota->no_of_leaves : $leaveType->no_of_leaves;
         $total_leave_count = Leave::getLeaveCounts(api_user()->id,$request->leave_type_id);
        $remaining_leave =   $totalAllowedLeaves - $total_leave_count;
        $sDate = Carbon::createFromFormat(company()->date_format, $request->multiStartDate);
        $eDate = Carbon::createFromFormat(company()->date_format, $request->multiEndDate);
        $multipleDates = CarbonPeriod::create($sDate, $eDate);
        foreach ($multipleDates as $multipleDate) {
            $multiDates[] = $multipleDate->format('Y-m-d');
        }
        /** @phpstan-ignore-next-line */
        
        $diffInDays = $sDate->diffInDays($eDate) + 1;
        if($request->start_second_half == true){
            $diffInDays = $diffInDays - 0.5;
        } 
        
        if($request->end_first_half == true){
            $diffInDays = $diffInDays - 0.5;
        }
         if($request->start_time){
             $diffInDays = $diffInDays - 0.5;
         }
          if($request->end_time){
             $diffInDays = $diffInDays - 0.5;
         }
        if($totalAllowedLeaves < $diffInDays) {
            return Reply::error(__('messages.leaveLimitError'));
        }
          if($remaining_leave < $diffInDays) {
            return Reply::error(__('messages.leaveLimitError'));
        }
        $uniqueId = Str::random(16);
        $employee = User::with('leaveTypes')->findOrFail($this->user->id);
        $employeeLeavesQuotas = $employee->leaveTypes;
        $allowedLeaves = clone $employeeLeavesQuotas;
        $totalEmployeeLeave = $allowedLeaves->sum('no_of_leaves');
         //check of max sequence_limit
         if ($leaveType->max_limit > 0) {
            $maxLimit = $leaveType->max_limit;
            $totalLeave = 0;
            // Loop through each past date in the sequence up to the max limit
            for ($i = 0; $i < $maxLimit; $i++) {
                $currentDate = $sDate->copy()->subDay()->startOfDay();
                $leaveCount = Leave::where('user_id', $this->user->id)
                    ->where('leave_type_id', $request->leave_type_id)
                    ->whereDate('leave_date', $currentDate)
                    ->count();
                // If leave count is 0 for any past date, stop counting backwards
                if ($leaveCount === 0) {
                    break;
                }
                $totalLeave += $leaveCount;
            }


            // Loop through each future date in the sequence up to the max limit
            for ($i = 0; $i < $maxLimit; $i++) {
                $currentDate = $eDate->copy()->addDay()->startOfDay();
                $leaveCount = Leave::where('user_id', $this->user->id)
                    ->where('leave_type_id', $request->leave_type_id)
                    ->whereDate('leave_date', $currentDate)
                    ->count();
                // If leave count is 0 for any future date, stop counting forwards
                if ($leaveCount === 0) {
                    break;
                }
                $totalLeave += $leaveCount;
            }
            // Add the leave days within the leave period
            $totalLeave += $diffInDays;
            if ($totalLeave > $leaveType->max_limit) {
                return Reply::error(__('messages.leaveLimitError'));
            }
        }
        //end  of max sequence_limit
          //check weekend sendwhich
          $previousDate = $sDate->copy()->subDays(1);
          $nextDate = $eDate->copy()->addDays(1);
          $attendanceSettings = AttendanceSetting::first()->shift;
          $officeOpenDays = $attendanceSettings->office_open_days;
          if (!in_array($previousDate->dayOfWeek, $officeOpenDays)) {
              $lastWorkingDayIndex = count($officeOpenDays) - 1;
             $newDate = $sDate->copy()->subDays($lastWorkingDayIndex - $sDate->copy()->dayOfWeek);
             $appliedsendwhichDateLeave =  Leave::with('type')->where('user_id', $this->user->id)
             ->where('leave_date',$newDate->copy()->format('Y-m-d'))
             ->whereIn('status', ['approved', 'pending'])
             ->first();
              $adjustedDate = [];
             if ($appliedsendwhichDateLeave) {
                 $currentDate = $newDate->addDays()->copy();
                 while ($currentDate->lessThan($sDate)) {
                     $adjustedDate[] = $currentDate->format('Y-m-d');
                     $currentDate->addDay();
                 } 
                 $sendwhich = true;
             }
          } 
          if (!in_array($nextDate->dayOfWeek, $officeOpenDays)) {
            $daysFarward = $sDate->copy()->dayOfWeek - $officeOpenDays[0];
            $index = array_search($daysFarward, $officeOpenDays);
            $newDate = $eDate->copy()->addDays($index);
            $appliedsendwhichDateLeave =  Leave::with('type')->where('user_id', $this->user->id)
            ->where('leave_date',$newDate->copy()->format('Y-m-d'))
            ->whereIn('status', ['approved', 'pending'])
            ->first();
            $adjustedDate = [];
            if ($appliedsendwhichDateLeave) {
                $currentDate = $newDate->copy();
                while ($eDate->lessThan($currentDate)) {
                    $adjustedDate[] = $eDate->format('Y-m-d');
                    $eDate->addDay();
                }   
                $sendwhich = true;
                unset($adjustedDate[0]);
            }
            }
            $checkHolidaySendwhich  = $this->checkHolidaySendwhich($sDate,$eDate,$request);
        if ($leaveType->monthly_limit > 0) {
            if ($request->duration != 'multiple') {
                $duration = match ($request->duration) {
                    'first_half', 'second_half' => 'half_day',
                    default => $request->duration,
                };

                $leaveTaken = LeaveType::byUser($this->user->id, $request->leave_type_id, array('approved', 'pending'), $request->leave_date);
                $leaveTaken = $leaveTaken->first();

                $dateApplied = Carbon::createFromFormat(company()->date_format, $request->leave_date);

                /** @phpstan-ignore-next-line */
                $currentMonthFullDay = Leave::whereBetween('leave_date', [$dateApplied->startOfMonth()->toDateString(), $dateApplied->endOfMonth()->toDateString()])
                    ->where('leave_type_id', $leaveType->id)
                    ->where('duration', '<>', 'half_day')
                    ->whereIn('status', ['approved', 'pending'])
                    ->where('user_id', $this->user->id)
                    ->get()->count();

                /** @phpstan-ignore-next-line */
                $currentMonthHalfDay = Leave::whereBetween('leave_date', [$dateApplied->startOfMonth()->toDateString(), $dateApplied->endOfMonth()->toDateString()])
                    ->where('leave_type_id', $leaveType->id)
                    ->where('duration', 'half_day')
                    ->whereIn('status', ['approved', 'pending'])
                    ->where('user_id', $this->user->id)
                    ->get()->count();

                /** @phpstan-ignore-next-line */
                $appliedLimit = ($currentMonthFullDay + ($currentMonthHalfDay / 2)) + (($duration == 'half_day') ? 0.5 : 1);
                /** @phpstan-ignore-next-line */
                if (!is_null($leaveTaken->leavesCount) && ((($leaveTaken->leavesCount->count - ($leaveTaken->leavesCount->halfday * 0.5)) + (($duration == 'half_day') ? 0.5 : 1)) > $totalAllowedLeaves)) {
                    return Reply::error(__('messages.leaveLimitError'));
                }

                if ($appliedLimit > $leaveType->monthly_limit) {
                    return Reply::error(__('messages.monthlyLeaveLimitError'));
                }


            }
            else {
                foreach ($multiDates as $dateData) {
                    $leaveTaken = LeaveType::byUser($this->user->id, $request->leave_type_id, array('approved', 'pending'), Carbon::parse($dateData)->format(company()->date_format));
                    $leaveTaken = $leaveTaken->first();

                    /** @phpstan-ignore-next-line */
                    if (!is_null($leaveTaken->leavesCount) && (($leaveTaken->leavesCount->count - ($leaveTaken->leavesCount->halfday * 0.5)) + count($multipleDates)) > $totalAllowedLeaves) {
                        return Reply::error(__('messages.leaveLimitError'));
                    }
                    elseif (count($multipleDates) > $totalAllowedLeaves) { /** @phpstan-ignore-line */
                        return Reply::error(__('messages.leaveLimitError'));
                    }

                    /** @phpstan-ignore-next-line */
                    array_push($multiDates, Carbon::parse($dateData)->format('Y-m-d'));
                }


                foreach ($multiDates as $dateData) {
                    $dateApplied = Carbon::parse($dateData);

                    /** @phpstan-ignore-next-line */
                    $currentMonthFullDay = Leave::whereBetween('leave_date', [$dateApplied->startOfMonth()->toDateString(), $dateApplied->endOfMonth()->toDateString()])
                        ->where('leave_type_id', $leaveType->id)
                        ->where('duration', '<>', 'half_day')
                        ->whereIn('status', ['approved', 'pending'])
                        ->where('user_id', $this->user->id)
                        ->get()->count();

                    /** @phpstan-ignore-next-line */
                    $currentMonthHalfDay = Leave::whereBetween('leave_date', [$dateApplied->startOfMonth()->toDateString(), $dateApplied->endOfMonth()->toDateString()])
                        ->where('leave_type_id', $leaveType->id)
                        ->where('duration', 'half_day')
                        ->whereIn('status', ['approved', 'pending'])
                        ->where('user_id', $this->user->id)
                        ->get()->count();

                    /** @phpstan-ignore-next-line */
                    $appliedLimit = ($currentMonthFullDay + ($currentMonthHalfDay / 2)) + count($multipleDates);

                    if ($appliedLimit > $leaveType->monthly_limit) {
                        return Reply::error(__('messages.monthlyLeaveLimitError'));
                    }
                }
            }
        }

        if ($request->duration == 'multiple') {
            $leaveTaken = LeaveType::byUser($this->user->id, $request->leave_type_id, array('approved', 'pending'), $request->leave_date);
            $leaveTaken = $leaveTaken->first();
            $leaveApplied = Leave::select(DB::raw('DATE_FORMAT(leave_date, "%Y-%m-%d") as leave_date_new'))
                ->where('user_id', $this->user->id)
                ->where('status', '!=', 'rejected')
                /** @phpstan-ignore-next-line */
                ->whereIn('leave_date', $multiDates)
                ->pluck('leave_date_new')
                ->toArray();

            if (!empty($leaveApplied)) {
                return Reply::error(__('messages.leaveApplyError'));
            }

            /* check leave limit for the selected leave type start */

            $holidays = Holiday::select(DB::raw('DATE_FORMAT(date, "%Y-%m-%d") as holiday_date'))
                ->whereIn('date', $multiDates)
                ->where('holiday_type','=',"Gazetted") /** @phpstan-ignore-line */
                ->pluck('holiday_date')->toArray();

            /** @phpstan-ignore-next-line */
            foreach ($multiDates as $date) {
                $dateInsert = Carbon::parse($date);

                if (!in_array($dateInsert, $holidays)) {
                    $leaveYear = Carbon::createFromFormat('d-m-Y', '01-'.company()->year_starts_from.'-'.$dateInsert->copy()->year)->startOfMonth();
            
                    if ($leaveYear->gt($dateInsert)) {
                        $leaveYear = $leaveYear->subYear();
                    }

                    $userTotalLeaves = Leave::byUserCount($this->user->id, $leaveYear->year);
                    $remainingLeave = $employeeLeaveQuota->no_of_leaves - $userTotalLeaves;

                    if(!is_null($leaveTaken->leavesCount) && ($leaveTaken->leavesCount->count + .5) == $employeeLeaveQuota->no_of_leaves) {
                        return Reply::error(__('messages.multipleRemainingLeaveError', ['leaves' => $remainingLeave]));
                    }

                    /** @phpstan-ignore-next-line */
                    if (!is_null($leaveTaken->leavesCount) && (($leaveTaken->leavesCount->count - ($leaveTaken->leavesCount->halfday * 0.5)) + count($multiDates)) > $employeeLeaveQuota->no_of_leaves) {
                        return Reply::error(__('messages.leaveLimitError'));
                    }
                    elseif (($userTotalLeaves + count($multiDates)) > $totalEmployeeLeave) { /** @phpstan-ignore-line */
                        return Reply::error(__('messages.leaveLimitError'));
                    }

                }
            }


            /* check leave limit for the selected leave type end */

            $leaveId = '';

            /** @phpstan-ignore-next-line */
            $multiDatesCount = count($multiDates);
            foreach ($multiDates as $index => $date) {
                $dateInsert = Carbon::parse($date)->format('Y-m-d');
                if (!in_array($dateInsert, $holidays)) {
                    $leave = new Leave();
                    $leave->user_id = $this->user->id;
                    $leave->unique_id = $uniqueId;
                    $leave->leave_type_id = $request->leave_type_id;
                    $leave->duration = $request->duration;
                    if ($index === 0 && $request->start_second_half == true){
                        $leave->duration = "half_day";
                        $leave->half_day_type = "second_half";
                       }
                     if ($index === ($multiDatesCount - 1) && $request->end_first_half == true) {
                      $leave->duration = 'half_day';
                      $leave->half_day_type = "first_half";
                     }
                    $leave->leave_date = $dateInsert;
                    $leave->start_time = $request->start_time;
                    $leave->end_time = $request->end_time;
                    $leave->reason = $request->reason;
                    $leave->paid = $leaveType->paid;
                    $leave->status = ($request->has('status') ? $request->status : 'pending');
                    $leave->save();
                    
                    if ($request->hasFile('file')) {
                foreach ($request->file as $file) {
               $leavefile = new LeaveFile();
               $filename = Files::uploadLocalOrS3($file, LeaveFile::FILE_PATH);
               $leavefile->user_id = api_user()->id;
               $leavefile->leave_id = $leave->id;
               $leavefile->filename = $filename;
               $leavefile->hashname = $file->hashName(); 
               $leavefile->size = $file->getSize();
               $leavefile->save();
               $updatedLeaves[] = $leavefile->id;
               }
                    }
          
                    $leaveId = $leave->id;
                }
            }
             //adjust wwekendsendwhich if availavle
             if($sendwhich){
                if($appliedsendwhichDateLeave->type->adjust_sandwich){
                    $adjust_sandwich_response = $this->adjustSandwichDate($adjustedDate,$appliedsendwhichDateLeave,$request);
                    if($adjust_sandwich_response){
                        return ApiResponse::make("Leave applied successfully. with Sandwich Policy", []);
                    }
                   }else{
                    $autolwp_response = $this->autoLwp($adjustedDate,$request);
                    if($autolwp_response){
                        return ApiResponse::make("Leave applied successfully. with Sandwich Policy", []);
                    }
                   }
             }
             if($checkHolidaySendwhich['status']){
                if($checkHolidaySendwhich['holiday_leave_type'] == "Restricted"){
                    
                    $this->adjustHolidaySendWhich($checkHolidaySendwhich['adjustLeaveAppplied'],$checkHolidaySendwhich['holidayLeaveApplied'],$request,$checkHolidaySendwhich['holiday_leave_type'],$checkHolidaySendwhich['holiday']);
                    return ApiResponse::make("Leave applied successfully. with Sandwich Policy", []);
                  }
                  if($checkHolidaySendwhich['holiday_leave_type'] == "Gazetted"){
                  $this->adjustHolidaySendWhich($checkHolidaySendwhich['adjustLeaveAppplied'],$checkHolidaySendwhich['holidayLeaveApplied'],$request,$checkHolidaySendwhich['holiday_leave_type'],$checkHolidaySendwhich['holiday']);
                  return ApiResponse::make("Leave applied successfully. with Sandwich Policy", []);
                  }
            }
            return ApiResponse::make(__('messages.leaveApplySuccess'), [
                'leaveID' => $leaveId,
            ]);
        }

        $dateInsert = Carbon::createFromFormat(company()->date_format, $request->leave_date)->format('Y-m-d');
        $leaveApplied = Leave::where('user_id', $this->user->id)
        ->where('status', '!=', 'rejected')
        ->whereDate('leave_date', $dateInsert)
        ->first();
        $holiday = Holiday::select(DB::raw('DATE_FORMAT(date, "%Y-%m-%d") as holiday_date'))->where('date', $dateInsert)->where('holiday_type','=',"Gazetted") ->first();
        if (!empty($leaveApplied) && $request->duration == 'single') {
            return Reply::error(__('messages.leaveApplyError'));
        }

        if (!is_null($holiday)) {
            return Reply::error(__('messages.holidayLeaveApplyError'));
        }

        /* check leave limit for the selected leave type start */
        $leaveYear = Carbon::createFromFormat('d-m-Y', '01-'.company()->year_starts_from.'-'.Carbon::parse($dateInsert)->year)->startOfMonth();

        if ($leaveYear->gt(Carbon::parse($dateInsert))) {
            $leaveYear = $leaveYear->subYear();
        }

        $leaveTaken = LeaveType::byUser($this->user->id, $request->leave_type_id, array('approved', 'pending'), $request->leave_date);
        $leaveTaken = $leaveTaken->first();

        $userTotalLeaves = Leave::byUserCount($this->user->id, $leaveYear->year);
        $remainingLeave = $employeeLeaveQuota->no_of_leaves - $userTotalLeaves;

        if(!is_null($leaveTaken->leavesCount) && ($leaveTaken->leavesCount->count + .5) == $employeeLeaveQuota->no_of_leaves && $request->duration == 'single') {
            return Reply::error(__('messages.multipleRemainingLeaveError', ['leaves' => $remainingLeave]));
        }

        /** @phpstan-ignore-next-line */
        if($request->duration == 'single'){
            if (!is_null($leaveTaken->leavesCount) && (($leaveTaken->leavesCount->count - ($leaveTaken->leavesCount->halfday * 0.5)) + 1) > $employeeLeaveQuota->no_of_leaves) {
                return Reply::error(__('messages.leaveLimitError'));
            }
            elseif (($userTotalLeaves + 1) > $totalEmployeeLeave) { /** @phpstan-ignore-line */
                return Reply::error(__('messages.leaveLimitError'));
            }
        }

        /* check leave limit for the selected leave type end */

        $duration = match ($request->duration) {
            'first_half', 'second_half' => 'half_day',
            default => $request->duration,
        };

        $leave = new Leave();
        $leave->user_id = $this->user->id;
        $leave->unique_id = $uniqueId;
        $leave->leave_type_id = $request->leave_type_id;
        $leave->duration = $duration;
        $leave->paid = $leaveType->paid;

        if ($duration == 'half_day') {
            /* check leave limit for the selected leave type start */
            $dateInsert = Carbon::createFromFormat(company()->date_format, $request->leave_date)->format('Y-m-d');

            $leaveApplied = Leave::where('user_id', $this->user->id)
            ->where('status', '!=', 'rejected')
            ->whereDate('leave_date', $dateInsert)->first();

            $userHalfDaysLeave = Leave::where([
                ['user_id', $this->user->id],
                ['status', '!=', 'rejected'],
                ['duration', $duration],
                ])->whereDate('leave_date', $dateInsert)->count();

            if($userHalfDaysLeave > 1){
                return Reply::error(__('messages.leaveApplyError'));
            }
            elseif(!is_null($leaveApplied) && $leaveApplied->duration != 'half_day') {
                return Reply::error(__('messages.leaveApplyError'));
            }
            elseif(!is_null($leaveApplied) && $leaveApplied->half_day_type == $request->duration){
                return Reply::error(__('messages.leaveApplyError'));
            }

            if (!is_null($leaveTaken->leavesCount) && (($leaveTaken->leavesCount->count - ($leaveTaken->leavesCount->halfday * 0.5)) + 0.5) > $employeeLeaveQuota->no_of_leaves) {
                return Reply::error(__('messages.leaveLimitError'));
            }
            elseif ($userTotalLeaves + 0.5 > $totalEmployeeLeave) { /** @phpstan-ignore-line */
                return Reply::error(__('messages.leaveLimitError'));
            }

            /* check leave limit for the selected leave type end */
            $leave->half_day_type = $request->duration;
        }

        $leave->leave_date = Carbon::createFromFormat(company()->date_format, $request->leave_date)->format('Y-m-d');
        $leave->reason = $request->reason;
        $leave->start_time = $request->start_time;
        $leave->end_time = $request->end_time;
        $leave->status = ($request->has('status') ? $request->status : 'pending');
        $leave->save();
        if ($request->hasFile('file')) {
                foreach ($request->file as $file) {
               $leavefile = new LeaveFile();
               $filename = Files::uploadLocalOrS3($file, LeaveFile::FILE_PATH);
               $leavefile->user_id = api_user()->id;
               $leavefile->leave_id = $leave->id;
               $leavefile->filename = $filename;
               $leavefile->hashname = $file->hashName(); 
               $leavefile->size = $file->getSize();
               $leavefile->save();
               $updatedLeaves[] = $leavefile->id;
               }
                    }
             $leaveId = $leave->id;

              //adjust wwekendsendwhich if availavle
              if($sendwhich){
                if($appliedsendwhichDateLeave->type->adjust_sandwich){
                    $adjust_sandwich_response = $this->adjustSandwichDate($adjustedDate,$appliedsendwhichDateLeave,$request);
                    if($adjust_sandwich_response){
                        return ApiResponse::make("Leave applied successfully. with Sandwich Policy", []);
                    }
                   }else{
                    $autolwp_response = $this->autoLwp($adjustedDate,$request);
                    if($autolwp_response){
                        return ApiResponse::make("Leave applied successfully. with Sandwich Policy", []);
                    }
                   }
             }
             if($checkHolidaySendwhich['status']){
                if($checkHolidaySendwhich['holiday_leave_type'] == "Restricted"){
                    $this->adjustHolidaySendWhich($checkHolidaySendwhich['adjustLeaveAppplied'],$checkHolidaySendwhich['holidayLeaveApplied'],$request,$checkHolidaySendwhich['holiday_leave_type'],$checkHolidaySendwhich['holiday']);
                    return ApiResponse::make("Leave applied successfully. with Sandwich Policy", []);
                  }
                  if($checkHolidaySendwhich['holiday_leave_type'] == "Gazetted"){
                  $this->adjustHolidaySendWhich($checkHolidaySendwhich['adjustLeaveAppplied'],$checkHolidaySendwhich['holidayLeaveApplied'],$request,$checkHolidaySendwhich['holiday_leave_type'],$checkHolidaySendwhich['holiday']);
                  return ApiResponse::make("Leave applied successfully. with Sandwich Policy", []);
                  }
            }
        return ApiResponse::make(__('messages.leaveApplySuccess'), [
            'leaveID' => $leaveId,
        ]);
    }
    public function leavelist()
    {
        $this->user = api_user();
          $leaveList = Leave::join('leave_types', 'leaves.leave_type_id', '=', 'leave_types.id')
         ->selectRaw('leaves.unique_id, MIN(leaves.leave_date) as start_date, leaves.created_at as applied_at, MAX(leaves.leave_date) as end_date, COUNT(*) as total_leave, leaves.duration, leaves.start_time, leaves.end_time, leaves.manager_status_permission, leave_types.type_name, leaves.status, leaves.reason, leaves.half_day_type, leave_types.color')
        ->where('leaves.user_id',  $this->user->id)
        ->groupBy('leaves.unique_id', 'leaves.duration', 'leaves.start_time', 'applied_at', 'leaves.end_time', 'leave_types.type_name', 'leaves.reason', 'leave_types.color', 'leaves.half_day_type', 'leaves.manager_status_permission', 'leaves.status')
        ->get()
        ->toArray();
        
        
        foreach ($leaveList as $i => $v) {
             $appliedAt = Carbon::parse($v['applied_at'])->setTimezone('Asia/Kolkata')->format("d M, D H:i A");
             $leaveList[$i]['applied_at'] = $appliedAt;
            $startDate = date('d M', strtotime($v['start_date']));
            $endDate = date('d M', strtotime($v['end_date']));
    
            // Check if start and end date are different
            if ($startDate !== $endDate) {
                $startDay = date('D', strtotime($v['start_date']));
                $endDay = date('D', strtotime($v['end_date']));
                $leaveList[$i]['leave_date'] = $startDate . ' - ' . $endDate . ', ' . $startDay . '-' . $endDay;
                $leaveList[$i]['total_leave'] = \Carbon\Carbon::parse($v['start_date'])->diffInDays(\Carbon\Carbon::parse($v['end_date'])) + 1;
            } else {
                $day = date('D', strtotime($v['start_date']));
                $leaveList[$i]['leave_date'] = $startDate . ', ' . $day;
                  if($leaveList[$i]['duration'] == "half_day"){
                $leaveList[$i]['total_leave'] = 0.5;
            }else{
                $leaveList[$i]['total_leave'] = 1;
            }
            }
            
            $leave = Leave::where('unique_id', $leaveList[$i]['unique_id'])->first();
            $leave_files = LeaveFile::where('leave_id', $leave->id)->get();
            $leaveList[$i]['file_name'] = [];
            if($leave_files->count()){
                foreach($leave_files as $leave_file){
                    $leaveList[$i]['file_name'][] = asset_url_local_s3('leave-files/' . $leave_file->filename, true, 'image');
                }
            }
            unset($leaveList[$i]['date']);
        }
    
        return ApiResponse::make(__('Data found successfully'), [
            'leave_list' => $leaveList,
        ]);
    }
    public function leaveType()
    {


        $unset_array = [
            "mobile_with_phonecode",
            "client_details",
            "employee_detail",
            "modules",
            "notice_period_start_date",
            "probation_end_date",
            "employee_department",
            "employee_designation",
            "maritalStatus",
            "usergender",
            "joining_date",
            "employeeLeave",
            "effective_type",
            "effective_after",
            "gender",
            "marital_status",
            "department",
            "designation",
            "allowed_probation",
            "role",
            "image_url"
        ];
        $this->user = api_user(); 
        $leaveTypes = User::join('employee_leave_quotas', 'users.id', '=', 'employee_leave_quotas.user_id')
            ->join('leave_types', 'employee_leave_quotas.leave_type_id', '=', 'leave_types.id')
            ->leftJoin('leaves', function ($join) {
                $join->on('employee_leave_quotas.leave_type_id', '=', 'leaves.leave_type_id')
                    ->where('leaves.user_id', '=', $this->user->id)
                    ->whereIn('leaves.status', ['approved', 'pending']);
            })
            ->join('employee_details', 'users.id', '=', 'employee_details.user_id')
            ->select(
                'employee_leave_quotas.user_id',
                'employee_leave_quotas.no_of_leaves',
                'leave_types.effective_type',
                'leave_types.effective_after',
                'leave_types.gender',
                'leave_types.marital_status',
                'leave_types.monthly_limit',
                'leave_types.department',
                'leave_types.designation',
                'leave_types.allowed_probation',
                'leave_types.type_name',
                'leave_types.role',
                'employee_leave_quotas.leave_type_id as leave_id',
                DB::raw('SUM(CASE WHEN leaves.id IS NOT NULL THEN 1 ELSE 0 END) as taken_leaves'),
                'employee_details.notice_period_start_date',
                'employee_details.probation_end_date',
                'employee_details.department_id as employee_department',
                'employee_details.designation_id as employee_designation',
                'employee_details.marital_status as maritalStatus',
                'users.gender as usergender',
                'employee_details.joining_date',
                'employee_leave_quotas.no_of_leaves as employeeLeave'
            )
            ->where('users.id', $this->user->id)
            ->groupBy(
                'employee_leave_quotas.user_id',
                'employee_leave_quotas.no_of_leaves',
                'leave_types.type_name',
                'leave_types.effective_type',
                'leave_types.effective_after',
                'leave_types.marital_status',
                'leave_types.department',
                'leave_types.designation',
                'leave_types.allowed_probation',
                'leave_types.gender',
                'leave_types.role',
                'employee_leave_quotas.leave_type_id',
                'employee_details.notice_period_start_date',
                'employee_details.probation_end_date',
                'employee_details.department_id',
                'employee_details.designation_id',
                'employee_details.marital_status',
                'users.gender',
                'employee_details.joining_date',
                'employee_leave_quotas.no_of_leaves'
                
            )
            ->get()
            ->toArray();
            $leaveTypeRole = $this->leaveTypeRole($this->user->id);
            foreach ($leaveTypes as $i => $v) {
                $leaveTypeCodition = LeaveType::leaveTypeCodition($leaveTypes[$i], $leaveTypeRole);
                if (!$leaveTypeCodition) {
                    unset($leaveTypes[$i]); // Removes the element if condition is not met
                }else {
                    $half_day_sum = Leave::where("duration", "=", "half_day")
                    ->where("leave_type_id", (int)$leaveTypes[$i]['leave_id'])
                    ->where('user_id', $this->user->id)
                    ->whereIn('leaves.status', ['approved', 'pending']);

                   $full_day_sum = Leave::where("leave_type_id", (int)$leaveTypes[$i]['leave_id'])
                    ->where('user_id', $this->user->id)
                    ->whereIn('leaves.status', ['approved', 'pending']);
               
                if ($leaveTypes[$i]['monthly_limit']) {
                    $half_day_sum->whereMonth('leave_date', Carbon::now()->month);
                    $full_day_sum->whereMonth('leave_date', Carbon::now()->month);
                    $leaveTypes[$i]["employeeLeave"]  =  $leaveTypes[$i]["employeeLeave"]/12;
                     $leaveTypes[$i]["no_of_leaves"]  =  $leaveTypes[$i]["no_of_leaves"]/12;
                  }
                $half_day_count = $half_day_sum->count();
                $full_day_count = $full_day_sum->count();
                
                $leaveTypes[$i]["taken_leaves"] = $full_day_count -  $half_day_count/2 ;
                 if ($leaveTypes[$i]["employeeLeave"] >= $leaveTypes[$i]["taken_leaves"]) {
                    $leaveTypes[$i]["remaining_leave"] = $leaveTypes[$i]["employeeLeave"] - $leaveTypes[$i]["taken_leaves"];
                } else {
                    $leaveTypes[$i]["remaining_leave"] = 0;
                }
                    // Unset specified fields
                    foreach ($unset_array as $field) {
                        unset($leaveTypes[$i][$field]);
                    }
                }
            }
            
         // Re-index the array after removal of elements
            $leaveTypes = array_values($leaveTypes);

        return ApiResponse::make(__('Data found successfully'), [
            'leave_types' => $leaveTypes,
        ]);
    }

    public function delete(Request $request)
    {
        $this->user = api_user(); 
        $deletedLeaves = Leave::where('unique_id', $request->unique_id)
        ->where('status', 'pending')
        ->where('user_id', $this->user->id)
        ->delete();
       
        
            return ApiResponse::make(__('Leave Deleted successfully'), [
                'deletedLeaves' => $deletedLeaves,
            ]);
    }
  
      public function TeamLeave($status = null)
    {
       
    $this->user = api_user(); 
    $leave_setting = LeaveSetting::where('company_id',$this->user->company_id)->select('manager_permission')->get();
    if(in_array('admin', user_roles())){
        $employeeLeaves = EmployeeDetails::where('user_id','!=',$this->user->id)->pluck('user_id');
    }else{
        $employeeLeaves = EmployeeDetails::where('reporting_to', $this->user->id)
        ->pluck('user_id');
    }

if ($employeeLeaves->isNotEmpty()) {
   $query = Leave::join('leave_types', 'leaves.leave_type_id', '=', 'leave_types.id')
    ->join('users', 'leaves.user_id', '=', 'users.id') 
    ->selectRaw('users.id as user_id, users.name, users.email, leaves.unique_id, MIN(leaves.leave_date) as start_date, MAX(leaves.leave_date) as end_date, leaves.duration, leaves.start_time, leaves.end_time, leave_types.type_name, leaves.status, leaves.reason, leave_types.color, leaves.half_day_type, 
     DATE_FORMAT(leaves.created_at, "%d %b, %a") as applied_at')
    ->whereIn('leaves.user_id', $employeeLeaves->toArray())
    ->groupBy('users.id', 'users.name', 'users.email', 'leaves.duration', 'leaves.half_day_type', 'leaves.start_time', 'leaves.end_time', 'leave_types.type_name', 'leaves.reason', 'leave_types.color', 'leaves.status', 'leaves.unique_id', 'applied_at');


if (!is_null($status)) {
    $query->where('leaves.status', $status);
}

$leaveList = $query->orderBy('leaves.id', 'DESC')->get()->toArray();

    foreach ($leaveList as $i => $v) {
        $startDate = date('d M', strtotime($v['start_date']));
        $endDate = date('d M', strtotime($v['end_date']));
       
        if ($startDate !== $endDate) {
            $startDay = date('D', strtotime($v['start_date']));
            $endDay = date('D', strtotime($v['end_date']));
            $leaveList[$i]['leave_date'] = $startDate . ' - ' . $endDate . ', ' . $startDay . '-' . $endDay;
             $leaveList[$i]['total_leave'] = \Carbon\Carbon::parse($v['start_date'])->diffInDays(\Carbon\Carbon::parse($v['end_date'])) + 1;
        } else {
            $day = date('D', strtotime($v['start_date']));
            $leaveList[$i]['leave_date'] = $startDate . ', ' . $day;
            if($leaveList[$i]['duration'] == "half_day"){
                $leaveList[$i]['total_leave'] = 0.5;
            }else{
                $leaveList[$i]['total_leave'] = 1;
            }
        }
       
         $leave = Leave::where('unique_id', $leaveList[$i]['unique_id'])->first();
        $leave_files = LeaveFile::where('leave_id', $leave->id)->get();
        $leaveList[$i]['file_name'] = [];
        if($leave_files->count()){
            foreach($leave_files as $leave_file){
                $leaveList[$i]['file_name'][] = asset_url_local_s3('leave-files/' . $leave_file->filename, true, 'image');
            }
        }
        unset($leaveList[$i]['date']);
       
    }
} else {
    $leaveList = []; 
}


    return ApiResponse::make(__('Data found successfully'), [
        'leaveList' =>$leaveList,
        'leave_setting' => $leave_setting
    ]); 
    }

public function teamMembers(){
    $this->user = api_user(); 
 if(in_array('admin', user_roles())){
        $teamMembers = EmployeeDetails::join('users', 'employee_details.user_id', '=', 'users.id') 
        ->select('users.id','users.name','users.email')
        ->where('users.id','!=',$this->user->id)
        ->get();
    }else{
        $teamMembers = EmployeeDetails::where('reporting_to', $this->user->id)
     ->join('users', 'employee_details.user_id', '=', 'users.id') 
     ->select('users.id','users.name','users.email')
     ->get();
    }
     return ApiResponse::make(__('Data found successfully'), [
        'teamMembers' =>$teamMembers,
    ]); 
}
public function leaveTypeRole($id)
{
    $roles = User::with('roles')->findOrFail($id);
    $userRole = [];
    $userRoles = $roles->roles->count() > 1 ? $roles->roles->where('name', '!=', 'employee') : $roles->roles;

    foreach($userRoles as $role){
        $userRole[] = $role->id;
    }

    $this->userRole = $userRole;
    return $this->userRole;
}

public function updateStatus(ActionLeave $request)
{
    $this->user = api_user();
    $employee_details = EmployeeDetails::with('user')->where("reporting_to", $this->user->id)
        ->where("user_id", $request->employee_id)
        ->first();

    $leave_setting = LeaveSetting::where('company_id', $this->user->company_id)
        ->value('manager_permission');

$leaves = Leave::where('unique_id', $request->unique_id)->get();

    if (!$leaves) {
        return ApiResponse::make(__('Leave not found'), [
            'updatedLeaves' => [],
        ]);
    }


foreach ($leaves as $leave) {
    $leave->approved_by = api_user()->id;
    $leave->approved_at = now();
    $leave->status = $request->status;
   //update  half day in attendance when approved  leave  of half Day
    try{
        if($leave->duration==='half_day'){
        $attendance = Attendance::where('user_id', $leave->user_id)
        ->where(function ($query) use ($leave) {
            $query->whereRaw('DATE(clock_in_time) = ?', [$leave->leave_date])
            ->orWhereRaw('DATE(clock_out_time) = ?', [$leave->leave_date]);
        })
        ->first();
        if (!is_null($attendance)) {
            Log::info('leave approved', ['user_id' =>$attendance]);
            // Perform your updates or further logic here
            $attendance->update(['half_day' => 'yes']);
        }
       }
        }catch(\Exception $e){
            
        }
        
    if (in_array('admin', user_roles())) {
        $employee_details = EmployeeDetails::with('user')->where("user_id", $request->employee_id)
            ->first();
        $leave->save();
    } else {
        if ($employee_details === null) {
            return ApiResponse::make(__('Permission Denied'), [
                'updatedLeaves' => [],
            ]);
        }
        if ($leave_setting == "pre-approve") {
            $leave->manager_status_permission = $leave_setting;
            $leave->status = $request->status == "rejected" ? "rejected" : "pending";

        } elseif ($leave_setting == "approved") {
            $title = "Dear ".$employee_details->user->name;
            if ($request->status == "approved") {
           
                $leave->approve_reason = $request->reason;
            } else {
                $leave->reject_reason = $request->reason;
            }
        } else {
            return ApiResponse::make(__('Permission Denied'), [
                'updatedLeaves' => [],
            ]);
        }
        $leave->save();
    }
}
    return ApiResponse::make(__('Status updated successfully'), [
        'updatedLeaves' => $leave->id, // Return the updated leave record
    ]);
}

public function addFile(Request $request){
    $updatedLeaves = [];
    $leave = Leave::where('unique_id', $request->leave_id)->first();
    foreach ($request->file as $file) {
        $leavefile = new LeaveFile();
        $filename = Files::uploadLocalOrS3($file, LeaveFile::FILE_PATH);
        $leavefile->user_id = api_user()->id;
        $leavefile->leave_id = $leave->id;
        $leavefile->filename = $filename;
        $leavefile->hashname = $file->hashName(); 
        $leavefile->size = $file->getSize();
        $leavefile->save();
        $updatedLeaves[] = $leavefile->id;
    }
    return ApiResponse::make(__('Files uploaded successfully'), [
        'updatedLeaves' => $updatedLeaves,
    ]); 
}
public function autoLwp($adjustedDate,$request){
    $lwp_leave_type  = LeaveType::where('type_name','=','LWP')->first();
    $uniqueId = Str::random(16);
    foreach ($adjustedDate as $index => $date) {
         $dateInsert = Carbon::parse($date)->format('Y-m-d');
             $leave = new Leave();
             $leave->user_id = api_user()->id;
             $leave->unique_id = $uniqueId;
             $leave->leave_type_id = $lwp_leave_type->id;
             $leave->duration = "multiple";
             $leave->leave_date = $dateInsert;
             $leave->reason = $request->reason;
             $leave->paid = $lwp_leave_type->paid;
             $leave->status = 'pending';
             $leave->save();
         }
         return true;
}
public function adjustSandwichDate($adjustedDate,$appliedsendwhichDateLeave,$request){
    $employeeLeaveQuota = EmployeeLeaveQuota::whereUserId(api_user()->id)->whereLeaveTypeId($appliedsendwhichDateLeave->type->id)->first();
    $totalAllowedLeaves = ($employeeLeaveQuota) ? $employeeLeaveQuota->no_of_leaves : $appliedsendwhichDateLeave->type->no_of_leaves;
    $leaveTaken = LeaveType::byUser(api_user()->id, $appliedsendwhichDateLeave->type->id, array('approved', 'pending'), $request->leave_date);
    $leaveTaken = $leaveTaken->first();
    $leaveApplied = Leave::select(DB::raw('DATE_FORMAT(leave_date, "%Y-%m-%d") as leave_date_new'))
        ->where('user_id', api_user()->id)
        ->where('status', '!=', 'rejected')
        ->whereIn('leave_date', $adjustedDate)
        ->pluck('leave_date_new')
        ->toArray();
    if (!empty($leaveApplied)) {
        return false;
    }
    foreach ($adjustedDate as $date) {
        $dateInsert = Carbon::parse($date);
            $leaveYear = Carbon::createFromFormat('d-m-Y', '01-'.company()->year_starts_from.'-'.$dateInsert->copy()->year)->startOfMonth();
            if ($leaveYear->gt($dateInsert)) {
                $leaveYear = $leaveYear->subYear();
            }
            $userTotalLeaves = Leave::byUserCount(api_user()->id, $leaveYear->year);
            $remainingLeave = $totalAllowedLeaves - $userTotalLeaves;

            if(!is_null($leaveTaken->leavesCount) && ($leaveTaken->leavesCount->count + .5) == $employeeLeaveQuota->no_of_leaves) {
                 if($this->autoLwp($adjustedDate,$request)){
                    return true;
                }
            }

            if (!is_null($leaveTaken->leavesCount) && (($leaveTaken->leavesCount->count - ($leaveTaken->leavesCount->halfday * 0.5)) + count($adjustedDate)) > $employeeLeaveQuota->no_of_leaves) {
                if($this->autoLwp($adjustedDate,$request)){
                    return true;
                }
            }
            elseif (($userTotalLeaves + count($adjustedDate)) > $totalAllowedLeaves) {
                if($this->autoLwp($adjustedDate,$request)){
                    return true;
                }
            }
    }

    $uniqueId = Str::random(16);
    foreach ($adjustedDate as $index => $date) {
         $dateInsert = Carbon::parse($date)->format('Y-m-d');
             $leave = new Leave();
             $leave->user_id = api_user()->id;
             $leave->unique_id = $uniqueId;
             $leave->leave_type_id = $appliedsendwhichDateLeave->type->id;
             $leave->paid = $appliedsendwhichDateLeave->type->paid;
             $leave->duration = "multiple";
             $leave->leave_date = $dateInsert;
             $leave->reason = $request->reason;
             $leave->status = 'pending';
             $leave->save();
         }
         return true;
}
public function checkHolidaySendwhich($sDate,$eDate,$request){
    $response['holidayLeaveApplied'] = null;
    $response['adjustLeaveAppplied'] = null;
    $response['holiday_leave_type'] = null;
    $response['holiday'] = null;
    $response['status'] = false;
    $multipleDates = CarbonPeriod::create($sDate, $eDate);
        foreach ($multipleDates as $multipleDate) {
            $multiDates[] = $multipleDate->format('Y-m-d');
        }
    //check sendwhich for forward date holiday
    $forwardDate = $eDate->copy()->addDays(1);
    $forwarDateHoliday = Holiday::whereDate('date',$forwardDate->copy()->format('Y-m-d'))->first();
    if($forwarDateHoliday){
       $holidayLeaveApplied = Leave::with('type')->whereDate('leave_date',$forwarDateHoliday->date->copy()->format('Y-m-d'))
       ->whereIn('status', ['approved', 'pending'])
       ->where('user_id','=',api_user()->id)->first();
       $adjustLeaveAppplied = Leave::with('type')->whereDate('leave_date',$forwarDateHoliday->date->copy()->addDays(1)->format('Y-m-d'))
       ->whereIn('status', ['approved', 'pending'])
       ->where('user_id','=',api_user()->id)->first();
      if($adjustLeaveAppplied && $holidayLeaveApplied && $holidayLeaveApplied->type->type_name == "Restricted Holiday" ) {
              $response['holidayLeaveApplied'] = $holidayLeaveApplied;
              $response['adjustLeaveAppplied'] = $adjustLeaveAppplied;
              $response['holiday_leave_type'] = "Restricted";
              $response['status'] = true;
        }
        if($adjustLeaveAppplied && $forwarDateHoliday){
            $response['holidayLeaveApplied'] = $holidayLeaveApplied;
            $response['adjustLeaveAppplied'] = $adjustLeaveAppplied;
            $response['holiday_leave_type'] = "Gazetted";
            $response['holiday'] = $forwarDateHoliday;
            $response['status'] = true;
        }
    }

    //check sendwhich for backdate holiday
    $backDate = $sDate->copy()->subDays(1);
    $backDateHoliday = Holiday::whereDate('date',$backDate->copy()->format('Y-m-d'))->first();
   
    if($backDateHoliday){
        $holidayLeaveApplied = Leave::with('type')->whereDate('leave_date',$backDateHoliday->date->copy()->format('Y-m-d'))
        ->whereIn('status', ['approved', 'pending'])
        ->where('user_id','=',api_user()->id)->first();
        $adjustLeaveAppplied = Leave::with('type')->whereDate('leave_date',$backDateHoliday->date->copy()->subDays(1)->format('Y-m-d'))
        ->whereIn('status', ['approved', 'pending'])
        ->where('user_id','=',api_user()->id)->first();
        if($adjustLeaveAppplied && $holidayLeaveApplied && $holidayLeaveApplied->type->type_name == "Restricted Holiday" ) {
            $response['holidayLeaveApplied'] = $holidayLeaveApplied;
            $response['adjustLeaveAppplied'] = $adjustLeaveAppplied;
            $response['holiday_leave_type'] = "Restricted";
            $response['status'] = true;
      }
      if($adjustLeaveAppplied && $backDateHoliday){
          $response['holidayLeaveApplied'] = $holidayLeaveApplied;
          $response['adjustLeaveAppplied'] = $adjustLeaveAppplied;
          $response['holiday_leave_type'] = "Gazetted";
          $response['holiday'] = $backDateHoliday;
          $response['status'] = true;
      }
     } 
    
     return  $response;
}
public function  adjustHolidaySendWhich($appliedsendwhichDateLeave,$holidayLeaveApplied,$request,$type,$holiday = null){
$uniqueId = Str::random(16);
$lwp_leave_type  = LeaveType::where('type_name','=','LWP')->first();
$employeeLeaveQuota = EmployeeLeaveQuota::whereUserId(api_user()->id)->whereLeaveTypeId($appliedsendwhichDateLeave->type->id)->first();
$totalAllowedLeaves = ($employeeLeaveQuota) ? $employeeLeaveQuota->no_of_leaves : $appliedsendwhichDateLeave->type->no_of_leaves;
$leaveTaken = LeaveType::byUser(api_user()->id, $appliedsendwhichDateLeave->type->id, array('approved', 'pending'), $request->leave_date);
$leaveTaken = $leaveTaken->first();
if($type == "Restricted Holiday"){
    if($leaveTaken->leavesCount->count+1 > $totalAllowedLeaves){
        $holidayLeaveApplied->leave_type_id =  $lwp_leave_type->id;
    }else{
        $holidayLeaveApplied->leave_type_id =  $appliedsendwhichDateLeave->type->id;
    }
    $holidayLeaveApplied->save();
}
if($type == "Gazetted"){
    $leave = new Leave();
    $leave->user_id = api_user()->id;
    $leave->unique_id = $uniqueId;
    $leave->duration = "single";
    $leave->leave_date = $holiday->date;
    $leave->start_time = $request->start_time;
    $leave->end_time = $request->end_time;
    $leave->status = 'pending';
    $leave->reason = $request->reason;
    if($leaveTaken->leavesCount->count+1 > $totalAllowedLeaves){
        $leave->paid = $lwp_leave_type->paid;
        $leave->leave_type_id = $lwp_leave_type->id;
    }else{
        $leave->paid = $appliedsendwhichDateLeave->type->paid;
        $leave->leave_type_id = $appliedsendwhichDateLeave->type->id;
    }
    $leave->save();
}
}
}