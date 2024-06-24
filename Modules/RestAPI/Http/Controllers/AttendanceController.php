<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Helper\Reply;
use App\Models\AttendanceSetting;
use App\Models\Company;
use App\Models\EmployeeDetails;
use App\Models\Holiday;
use Carbon\Carbon;
use App\Models\AssignBranch;
use Froiden\RestAPI\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\RestAPI\Entities\Attendance;
use App\Models\EmployeeShiftSchedule;
use App\Models\CompanyAddress;
use Modules\RestAPI\Entities\Leave;
use Modules\RestAPI\Entities\User;
use Modules\RestAPI\Http\Requests\Attendance\CreateRequest;
use Modules\RestAPI\Http\Requests\Attendance\DeleteRequest;
use Modules\RestAPI\Http\Requests\Attendance\IndexRequest;
use Modules\RestAPI\Http\Requests\Attendance\ShowRequest;
use Modules\RestAPI\Http\Requests\Attendance\UpdateRequest;
use Illuminate\Support\Facades\Log;


class AttendanceController extends ApiBaseController
{
    protected $model = Attendance::class;

    protected $indexRequest = IndexRequest::class;

    protected $storeRequest = CreateRequest::class;

    protected $updateRequest = UpdateRequest::class;

    protected $showRequest = ShowRequest::class;

    protected $deleteRequest = DeleteRequest::class;
    protected function modifyIndex($query)
    {
        return $query->groupBy('attendances.user_id')->visibility();
    }
    public function today()
    {
        $attendanceSettings = EmployeeShiftSchedule::where('user_id', api_user()->id)
            ->whereDate('date', Carbon::today())
            ->first();

        if ($attendanceSettings) {
            $attendanceSettings = $attendanceSettings->shift;
        } else {
            $attendanceSettings = AttendanceSetting::first()->shift;
        }
        $maxAttendanceInDay = $attendanceSettings->clockin_in_day;
        $attendance = Attendance::where('user_id', api_user()->id)->where(DB::raw('DATE(clock_in_time)'), Carbon::today()->format('Y-m-d'))
            ->select(['id', 'clock_in_latitude', 'attendance_status', 'clock_in_longitude', 'clock_in_time', 'clock_out_time', 'clock_out_latitude', 'clock_out_longitude', 'late', 'location'])
            ->get();

        $todayTotalClockin = count($attendance);
        $data['remaining_clock_in'] = $maxAttendanceInDay - $todayTotalClockin;
        $data['status'] = 'Absent';
        $data['date'] = Carbon::today()->format('d M Y');
        $data['day'] = Carbon::today()->format('D');
        $data['attendance_status'] = null;
        $data['attendanceIn']['latitude'] = null;
        $data['attendanceIn']['longitude'] = null;
        $data['attendanceIn']['InTime'] = null;
        $data['attendanceIn']['late'] = null;
        $data['attendanceOut']['OutTime'] = null;
        $data['attendanceOut']['latitude'] = null;
        $data['attendanceOut']['longitude'] = null;
        $data['leave']['date'] = null;
        $data['leave']['leaveType'] = null;
        $data['leave']['start_time'] = null;
        $data['leave']['end_time'] = null;
        $data['leave']['color'] = null;
        $data['leave']['duration'] = null;
        $data['holiday']['id'] = null;
        $data['holiday']['occassion'] = null;
        $data['location'] = false;
        $data['holiday']['date'] = null;

        $leave = Leave::with('type')->where('user_id', api_user()->id)
            ->whereDate('leave_date', '=', Carbon::today()->format('Y-m-d'))
            ->where('status', '=', 'approved')
            ->first();

        $holiday = Holiday::where('holiday_type', 'Gazetted')
            ->whereDate('date', '=', Carbon::today()->format('Y-m-d'))
            ->first();
        $data['status'] = in_array(Carbon::today()->dayOfWeek, $attendanceSettings->office_open_days) ? $data['status'] : "Week Off";


        if ($leave) {
            $data['status'] = 'On Leave';
            $data['leave']['date'] = $leave->leave_date->format('Y-m-d');
            $data['leave']['leaveType'] = $leave->type->type_name;
            $data['leave']['start_time'] = $leave->start_time;
            $data['leave']['end_time'] = $leave->end_time;
            $data['leave']['color'] = $leave->type->color;
            $data['leave']['duration'] = $leave->type->type_name == "Short Leave" ? "Short Leave" : $leave->duration;
            $data['leave']['half_day_type'] = $leave->half_day_type;
        }

        if ($holiday) {
            $data['status'] = 'Holiday';
            $data['holiday']['date'] = $holiday->date->format('Y-m-d');
            $data['holiday']['occassion'] = $holiday->occassion;
        }

        if ($todayTotalClockin) {
            $data['status'] = 'present';
            $data['location'] = $attendance->first()['location'];
            $data['attendance_status'] = $attendance->last()['attendance_status'];
            $data['attendanceIn']['latitude'] = $attendance->first()['clock_in_latitude'] ?? '';
            $data['attendanceIn']['longitude'] = $attendance->first()['clock_in_longitude'] ?? '';
            $data['attendanceIn']['InTime'] = Carbon::parse($attendance->first()['clock_in_time'])
                ->setTimezone('Asia/Kolkata')
                ->format('h:i A');
            $data['attendanceIn']['late'] = $attendance->first()['late'];

            $data['attendanceOut']['OutTime'] = $attendance->last()['clock_out_time'] ? Carbon::parse($attendance->last()['clock_out_time'])
                ->setTimezone('Asia/Kolkata')
                ->format('h:i A') : '';
            $data['attendanceOut']['latitude'] = $attendance->last()['clock_out_latitude'] ?? '';
            $data['attendanceOut']['longitude'] = $attendance->last()['clock_out_longitude'] ?? '';
        }
        if ($data['status'] == "present") {
            $data['Banner']['visible'] = False;
            $data['Banner']['banner_url'] = null;
        } else {
            $data['Banner']['visible'] = True;
        }
        if ($data['status'] == "Absent") {
            $data['Banner']['banner_url'] = $data['status'];
        }
        if ($data['status'] == "Week Off") {
            $data['Banner']['banner_url'] = asset_url_local_s3('status/weekend.png');
        }
        if ($data['status'] == "On Leave") {
            $data['Banner']['banner_url'] = asset_url_local_s3('status/leave.png');
        }
        if ($data['status'] == "Holiday") {
            $data['Banner']['banner_url'] = asset_url_local_s3('status/holiday.jpg');
        }
        $data['Banner']['status'] = $data['status'];

        return ApiResponse::make('data found successfully', $data);

    }
   public function clockIn()
    {

        $now = now();

        $this->user = api_user();
        $this->global = api_user()->company;
        DB::statement(
            'INSERT INTO attendances_log (company_id, user_id, latitude,longitude,type) VALUES (?, ?, ?, ?, ?)',
            [
                $this->user->company_id,
                $this->user->id,
                request()->currentLongitude,
                request()->currentLongitude,
                "In"
            ]
        );
        $attendanceSettings = EmployeeShiftSchedule::where('user_id', api_user()->id)
            ->whereDate('date', Carbon::today())
            ->first();

        if ($attendanceSettings) {
            $attendanceSettings = $attendanceSettings->shift;
        } else {
            $attendanceSettings = AttendanceSetting::first();
            if ($attendanceSettings) {
                $attendanceSettings = $attendanceSettings->shift;
            } else {
                $attendanceSettings = api_user()->company->attendanceSetting;
            }
        }
        $clockInCount = \App\Models\Attendance::getTotalUserClockIn($now->format('Y-m-d'), $this->user->id);
        if ($attendanceSettings->ip_check == 'yes') {
            $ips = (array) json_decode($attendanceSettings->ip_address);

            if (!in_array(request()->ip(), $ips)) {
                return Reply::error(__('messages.notAnAuthorisedDevice'));
            }
        }

        $radiusSettings = AttendanceSetting::first();
      if ($radiusSettings->radius_check == 'yes' && !(isset(request()->outside_reason)) && api_user()->ood !== "enable") {
            $checkRadius = $this->isWithinRadius(request());

            if (!$checkRadius) {
                return Reply::error(__('messages.notAnValidLocation'));
            }
        }
        if ($clockInCount >= 1) {
            $checkTodayAttendance = Attendance::where('user_id', $this->user->id)
                ->where(DB::raw('DATE(attendances.clock_in_time)'), '=', $now->format('Y-m-d'))->get();
            foreach ($checkTodayAttendance as $att) {
                if ($att->clock_in_time && is_null($att->clock_out_time)) {
                    return Reply::error(__('messages.alreadyCheckedIn'));
                }
            }
        }
        // Check maximum attendance in a day
        if ($clockInCount < $attendanceSettings->clockin_in_day) {
            // Set TimeZone And Convert into timestamp

            $currentTimestamp = $now->setTimezone('UTC');
            $currentTimestamp = $currentTimestamp->timestamp;
            $halfDayTimestamp = null;

            // Set TimeZone And Convert into timestamp in half daytime
            if ($attendanceSettings->halfday_mark_time) {
                $halfDayTimestamp = $now->format('Y-m-d') . ' ' . $attendanceSettings->halfday_mark_time;
                $halfDayTimestamp = Carbon::createFromFormat('Y-m-d H:i:s', $halfDayTimestamp, $this->global->timezone);
                $halfDayTimestamp = $halfDayTimestamp->setTimezone('UTC');
                $halfDayTimestamp = $halfDayTimestamp->timestamp;
            }

            $timestamp = $now->format('Y-m-d') . ' ' . $attendanceSettings->office_start_time;
            $officeStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, $this->global->timezone);
            $officeStartTime = $officeStartTime->setTimezone('UTC');

            $lateTime = $officeStartTime->addMinutes($attendanceSettings->late_mark_duration);

            $checkTodayAttendance = Attendance::where('user_id', $this->user->id)
                ->where(DB::raw('DATE(attendances.clock_in_time)'), '=', $now->format('Y-m-d'))->first();

            $attendance = new \App\Models\Attendance;
            $attendance->user_id = $this->user->id;
            $attendance->attendance_status = 0;
            $attendance->clock_in_time = $now;
            $attendance->clock_in_ip = request()->ip();
            $attendance->clock_in_longitude = request()->currentLongitude;
            $attendance->clock_in_latitude = request()->currentLatitude;
            $attendance->location = isset(request()->outside_reason) || api_user()->ood == "enable" ? false : true;
           $attendance->clock_in_outside_reason = isset(request()->outside_reason) ? request()->outside_reason:null;
           $attendance->status = isset(request()->outside_reason) || api_user()->ood == "enable" ? "pending" : "approved";
           $attendance->working_from = isset(request()->outside_reason)  || api_user()->ood == "enable" ? "outside" :"office";
            if ($now->gt($lateTime) && is_null($checkTodayAttendance)) {
                $attendance->late = 'yes';
            }
            $attendance->half_day = 'no'; 
            // Check day's first record and half day time
            if (!is_null($attendanceSettings->halfday_mark_time) && is_null($checkTodayAttendance) && $currentTimestamp > $halfDayTimestamp) {
                $attendance->half_day = 'yes';
            }

            try {
                 // If leave already applied first half or second half Hari;
            $leaveHalfDay = Leave::whereRaw('leave_date = DATE(?)', [$attendance->clock_in_time])
            ->where('user_id', $attendance->user_id)
            ->where(function($query) {
                $query->where('duration', 'half_day');
            })->first();

            if(!is_null($leaveHalfDay)){
                Log::info('Attendance clock in', ['user_id' =>$leaveHalfDay]);
                $attendance->half_day = 'yes';
            }
            
            } catch (\Exception $e) {

            }
            
            $attendance->save();

            return ApiResponse::make('Clocked in successfully', [
                'attendance_status' => $attendance->attendance_status,
            ]);
        }
        return Reply::error(('Attendance completed'));
    }
     public function clockOut()
    {
        $now = now();
        $this->user = api_user();
        $this->global = api_user()->company;
        DB::statement(
            'INSERT INTO attendances_log (company_id, user_id, latitude,longitude,type) VALUES (?, ?, ?, ?, ?)',
            [
                $this->user->company_id,
                $this->user->id,
                request()->currentLongitude,
                request()->currentLongitude,
                "Out"
            ]
        );
        $attendanceSettings = EmployeeShiftSchedule::where('user_id', api_user()->id)
            ->whereDate('date', Carbon::today())
            ->first();

        if ($attendanceSettings) {
            $attendanceSettings = $attendanceSettings->shift;
        } else {
            $attendanceSettings = AttendanceSetting::first();
            if ($attendanceSettings) {
                $attendanceSettings = $attendanceSettings->shift;
            } else {
                $attendanceSettings = api_user()->company->attendanceSetting;
            }
        }

        $todayCheckAttendance = Attendance::where('user_id', $this->user->id)
            ->where(DB::raw('DATE(attendances.clock_in_time)'), '=', $now->format('Y-m-d'))->get();
        $id = '';
        if (!$todayCheckAttendance) {
            return Reply::error("You are not yet Clocked In");
        } else {
            foreach ($todayCheckAttendance as $index => $att) {
                if ($index === $todayCheckAttendance->count() - 1 && !is_null($att->clock_out_time)) {
                    return Reply::error(__('messages.alreadyClockOut'));
                } else {
                    $id = $att->id;
                }
            }
        }
        // Check user by ip
        if ($attendanceSettings->ip_check == 'yes') {
            $ips = (array) json_decode($attendanceSettings->ip_address);

            if (!in_array(request()->ip(), $ips)) {
                return Reply::error(__('messages.notAnAuthorisedDevice'));
            }
        }

        $radiusSettings = AttendanceSetting::first();
        if ($radiusSettings->radius_check == 'yes' && !(isset(request()->outside_reason)) && api_user()->ood !== "enable") {
            $checkRadius = $this->isWithinRadius(request());

            if (!$checkRadius) {
                return Reply::error(__('messages.notAnValidLocation'));
            }
        }
        $attendance = Attendance::findOrFail($id);

        $clockInCount = $todayCheckAttendance->count();
        $attendance->attendance_status = ($clockInCount == $attendanceSettings->clockin_in_day) ? 2 : 1;
        $attendance->clock_out_time = $now;
        $attendance->clock_out_longitude = request()->currentLongitude;
        $attendance->clock_out_latitude = request()->currentLatitude;
        $attendance->clock_out_ip = request()->ip();
        $attendance->clock_out_outside_reason = isset(request()->outside_reason) ? request()->outside_reason:null;
        $attendance->status = isset(request()->outside_reason) ? "pending" : "approved";
        $attendance->location = isset(request()->outside_reason) || api_user()->ood == "enable" ? false : true;
        $attendance->status = isset(request()->outside_reason) || api_user()->ood == "enable" ? "pending" : "approved";
        $attendance->working_from = isset(request()->outside_reason)  || api_user()->ood == "enable" ? "outside" :"office";

        $attendance->save();

        return ApiResponse::make('Clocked out successfully', [
            'attendance_status' => $attendance->attendance_status,
        ]);
    }

    private function isWithinRadius($request)
    {

        $attendanceSettings = AttendanceSetting::first();
        $radius = $attendanceSettings->radius;
        $currentLatitude = $request->currentLatitude;
        $currentLongitude = $request->currentLongitude;
        $location = CompanyAddress::where('company_id', api_user()->company_id)->first();


        $apiCompanyId = api_user()->company_id;
        $branch = AssignBranch::with([
            'branch' => function ($query) use ($apiCompanyId) {
                $query->where('company_id', $apiCompanyId);
            }
        ])->where('user_id', api_user()->id)->first();
        if ($branch !== NULL) {
            $latFrom = deg2rad($branch->branch->lat);
            $lonFrom = deg2rad($branch->branch->long);
        } else {
            $latFrom = deg2rad($location->latitude);
            $lonFrom = deg2rad($location->longitude);
        }


        $latTo = deg2rad($currentLatitude);
        $lonTo = deg2rad($currentLongitude);

        $theta = $lonFrom - $lonTo;

        $dist = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($theta);
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $distance = $dist * 60 * 1.1515 * 1609.344;

        return $distance <= $radius;
    }

    public function attendance(Request $request)
    {

        if ($request->employee_id !== null) {
            $user_id = $request->employee_id;
        } else {
            $user_id = api_user()->id;
        }

        $this->global = Company::first();
        $this->currentMonth = Carbon::parse('01-' . $request->month . '-' . $request->year);
        $this->daysInMonth = Carbon::parse('01-' . $request->month . '-' . $request->year)->daysInMonth;

        $requestedDate = Carbon::parse(Carbon::parse('01-' . $request->month . '-' . $request->year))->endOfMonth();
        $now = Carbon::now()->timezone($this->global->timezone);

        $requestedDate = Carbon::parse(Carbon::parse('01-' . $request->month . '-' . $request->year))->endOfMonth();

        $leave = [];
        $total_month_days = array();

        if ($requestedDate->isPast()) {
            $dataTillToday = array_fill(1, $this->daysInMonth, 'Absent');
        } else {
            $dataTillToday = array_fill(1, $now->copy()->format('d'), 'Absent');
        }

        if (($now->copy()->daysInMonth != $this->daysInMonth) && !$requestedDate->isPast()) {
            $dataTillToday = array_fill($now->copy()->addDay()->format('d'), ($this->daysInMonth - $now->copy()->format('d')), '-');
            return Reply::error(__('not marked'));
        }

        foreach ($dataTillToday as $i => $v) {
            $total_month_days[] = $i;
        }
        foreach ($total_month_days as $i => $v) {
            $attendanceSettings = EmployeeShiftSchedule::with('shift')->where('user_id', $user_id)
                ->whereDate('date', Carbon::parse($v . '-' . $request->month . '-' . $request->year)->format('Y-m-d'))
                ->first();
            if ($attendanceSettings) {
                $attendanceSettings = $attendanceSettings->shift;
            } else {
                $attendanceSettings = AttendanceSetting::first()->shift;
            }
            $checkTodayHoliday = Holiday::where('date', Carbon::parse($v . '-' . $request->month . '-' . $request->year)->format('Y-m-d'))->where('holiday_type', 'Gazetted')->select(['id', 'occassion', 'date'])->first();
            $checkTodayLeave = Leave::with('type')->where('user_id', $user_id)
                ->where('leave_date', '=', Carbon::parse($v . '-' . $request->month . '-' . $request->year)->format('Y-m-d'))
                ->where('status', '=', 'approved')
                ->first();
            $checkTodayPendingLeave = Leave::with('type')->where('user_id', $user_id)
                ->where('leave_date', '=', Carbon::parse($v . '-' . $request->month . '-' . $request->year)->format('Y-m-d'))
                ->where('status', '=', 'pending')
                ->first();

            $attendance = Attendance::where('user_id', $user_id)
                ->select(['id', 'clock_in_latitude', 'clock_in_longitude', 'clock_in_time', 'late', 'clock_out_time', 'clock_out_latitude', 'clock_out_longitude', 'late', 'location'])
                ->where(DB::raw('DATE(clock_in_time)'), Carbon::parse($request->year . '-' . $request->month . '-' . $v)->format('Y-m-d'))
                ->get();
            $data['status'] = 'Absent';
            $data['date'] = Carbon::parse($v . '-' . $request->month . '-' . $request->year)->format('d M Y');
            $date = Carbon::parse($v . '-' . $request->month . '-' . $request->year);
            $dayOfWeek = $date->dayOfWeek;
            $data['day'] = Carbon::parse($v . '-' . $request->month . '-' . $request->year)->format('D');
            $data['attendance_status'] = null;
            $data['location'] = false;
            $data['attendanceIn']['latitude'] = null;
            $data['attendanceIn']['longitude'] = null;
            $data['attendanceIn']['InTime'] = null;
            $data['attendanceIn']['late'] = null;
            $data['attendanceOut']['OutTime'] = null;
            $data['attendanceOut']['latitude'] = null;
            $data['attendanceOut']['longitude'] = null;
            $data['leave']['date'] = null;
            $data['leave']['leaveType'] = null;
            $data['leave']['start_time'] = null;
            $data['leave']['end_time'] = null;
            $data['leave']['color'] = null;
            $data['leave']['duration'] = null;
            $data['leave']['leave_applied'] = false;
            $data['holiday']['id'] = null;
            $data['holiday']['occassion'] = null;
            $data['holiday']['date'] = null;

            $data['status'] = in_array($dayOfWeek, array_map('intval', $attendanceSettings->office_open_days)) ? $data['status'] : "Weekend";

            if ($checkTodayHoliday) {
                $data['status'] = 'holiday';
                $data['holiday']['id'] = $checkTodayHoliday->id;
                $data['holiday']['occassion'] = $checkTodayHoliday->occassion;
                $data['holiday']['date'] = $checkTodayHoliday->date->format('Y-m-d');
            }
            if ($checkTodayLeave) {
                $data['status'] = 'On Leave';
                $data['leave']['date'] = $checkTodayLeave->leave_date->format('Y-m-d');
                $data['leave']['leaveType'] = $checkTodayLeave->type->type_name;
                $data['leave']['start_time'] = $checkTodayLeave->start_time;
                $data['leave']['end_time'] = $checkTodayLeave->end_time;
                $data['leave']['color'] = $checkTodayLeave->type->color;
                $data['leave']['duration'] = $checkTodayLeave->type->type_name == "Short Leave" ? "Short Leave" : $checkTodayLeave->duration;
                $data['leave']['half_day_type'] = $checkTodayLeave->half_day_type;
                $data['leave']['leave_applied'] = true;
            }


            if ($checkTodayPendingLeave) {
                $data['leave']['leave_applied'] = true;
            }
            if ($attendance->isNotEmpty()) {
                 if($checkTodayLeave){
                      $late = $this->checkLate($attendance->first()['user_id'],$attendance->first()['clock_in_time'],$attendanceSettings,$checkTodayLeave);
                   }else{
                       $late  = $attendance->first()['late'] ?? '';
                   }
                $data['location'] = $attendance->first()['location'];
                $data['status'] = 'present';
                $data['attendance_status'] = $attendance->last()['attendance_status'];
                $data['attendanceOut']['OutTime'] = $attendance->last()['clock_out_time'] ? Carbon::parse($attendance->last()['clock_out_time'])
                    ->setTimezone('Asia/Kolkata')
                    ->format('H:i') : '';
                $data['attendanceOut']['latitude'] = $attendance->last()['clock_out_latitude'] ?? '';
                $data['attendanceOut']['longitude'] = $attendance->last()['clock_out_longitude'] ?? '';
                $data['attendanceIn']['latitude'] = $attendance->first()['clock_in_latitude'] ?? '';
                $data['attendanceIn']['longitude'] = $attendance->first()['clock_in_longitude'] ?? '';
                $data['attendanceIn']['InTime'] = $attendance->first()['clock_in_time'] ? Carbon::parse($attendance->first()['clock_in_time'])
                    ->setTimezone('Asia/Kolkata')
                    ->format('H:i') : '';
                $data['attendanceIn']['late'] = $late;



            }
            $total_month_days[$i] = $data;
        }

        if ($total_month_days) {
            return ApiResponse::make('Data found successfully', $total_month_days);
        } else {
            return ApiResponse::make('Data not found', null);
        }
    }

    public function teamAttendance(Request $request)
    {
        $this->employee_id = $request->employee_id;
        $this->day = $request->day;
        $this->month = $request->month;
        $this->year = $request->year;
        $this->today_date = Carbon::parse($request->day . '-' . $request->month . '-' . $request->year)->format('Y-m-d');
        $this->user = api_user();

        if (in_array('admin', user_roles())) {
            $employeeIds = EmployeeDetails::where('user_id', '!=', $this->user->id)
                ->pluck('user_id');
        } else {
            $employeeIds = EmployeeDetails::where('reporting_to', $this->user->id)
                ->pluck('user_id');
        }

        $attendance = User::whereIn('users.id', $employeeIds->toArray())
            ->leftJoin('attendances', function ($join) use ($employeeIds) {
                $join->on('users.id', '=', 'attendances.user_id')
                    ->whereDate('attendances.clock_in_time', $this->today_date);
            })
            ->select([
                'users.id as user_id',
                'users.name as name',
                'users.image as image',
                'attendances.id as attendance_id',
                'attendances.clock_in_time',
                'attendances.clock_in_longitude',
                'attendances.clock_in_latitude',
                'attendances.clock_out_time',
                'attendances.clock_out_longitude',
                'attendances.clock_out_latitude',
                'attendances.attendance_status',
                'attendances.late',
                'attendances.location',


            ])
            ->orderBy('attendances.id', 'DESC')->get();
        $formattedData = array();
        foreach ($attendance->groupBy('name') as $userId => $userAttendance) {
            $attendanceSettings = EmployeeShiftSchedule::with('shift')->where('user_id', $userAttendance->first()['user_id'])
                ->whereDate('date', $this->today_date)
                ->first();
            if ($attendanceSettings) {
                $attendanceSettings = $attendanceSettings->shift;
            } else {
                $attendanceSettings = AttendanceSetting::first()->shift;
            }
            $emp = EmployeeDetails::where('user_id', '=', $userAttendance->first()['user_id'])
                ->first();
            $data['status'] = 'Absent';
            $data['employee_id'] = $emp->employee_id;
            $data['user_id'] = $userAttendance->first()['user_id'];
            $data['name'] = $userAttendance->first()['name'];
            $data['email'] = $userAttendance->first()['email'];
            $gravatarHash = md5(strtolower(trim($data['email'])));
            $data['image'] = $userAttendance->first()['image'] ? asset_url_local_s3('avatar/' . $userAttendance->first()['image'], true, 'image') : 'https://www.gravatar.com/avatar/' . $gravatarHash . '.png?s=200&d=mp';
            $data['department'] = $emp && $emp->department?$emp->department->team_name:"";
            $data['designation'] = $emp && $emp->designation?$emp->designation->name:"";
            $data['date'] = Carbon::parse($request->day . '-' . $request->month . '-' . $request->year)->format('d M Y');
            $data['day'] = Carbon::parse($request->day . '-' . $request->month . '-' . $request->year)->format('D');
            $day_of_week = Carbon::parse($request->day . '-' . $request->month . '-' . $request->year)->dayOfWeek;

            $data['attendance_status'] = null;
            $data['location'] = false;
            $data['attendanceIn']['latitude'] = null;
            $data['attendanceIn']['longitude'] = null;
            $data['attendanceIn']['InTime'] = null;
            $data['attendanceIn']['late'] = null;
            $data['attendanceOut']['OutTime'] = null;
            $data['attendanceOut']['latitude'] = null;
            $data['attendanceOut']['longitude'] = null;
            $data['leave']['date'] = null;
            $data['leave']['leaveType'] = null;
            $data['leave']['start_time'] = null;
            $data['leave']['end_time'] = null;
            $data['leave']['color'] = null;
            $data['leave']['duration'] = null;
            $data['leave']['half_day_type'] = null;
            $data['holiday']['id'] = null;
            $data['holiday']['occassion'] = null;
            $data['holiday']['date'] = null;

            $leave = Leave::with('type')
                ->where('user_id', $userAttendance->first()['user_id'])
                ->where('leave_date', $this->today_date)
                ->where('status', '=', 'approved')
                ->first();

            $holiday = Holiday::where('holiday_type', 'Gazetted')
                ->where('date', $this->today_date)
                ->first();

            $data['status'] = in_array($day_of_week, array_map('intval', $attendanceSettings->office_open_days)) ? $data['status'] : "Week Off";

            if ($holiday) {
                $data['status'] = 'Holiday';
                $data['holiday']['date'] = Carbon::parse($holiday->date)->format('Y-m-d');
                $data['holiday']['occassion'] = $holiday->occassion;
            }
            if ($leave) {
                $data['status'] = 'On Leave';
                $data['leave']['date'] = Carbon::parse($leave['leave_date'])->format('Y-m-d');
                $data['leave']['leaveType'] = $leave['type']['type_name'];
                $data['leave']['start_time'] = $leave['start_date'];
                $data['leave']['end_time'] = $leave['end_date'];
                $data['leave']['color'] = $leave['type']['color'];
                $data['leave']['duration'] = ($leave['type']['type_name'] == "Short Leave") ? "Short Leave" : $leave['duration'];
                $data['leave']['half_day_type'] = $leave['half_day_type'];
            }

            if ($userAttendance->isNotEmpty() && $userAttendance->first()['clock_in_time']) {
                  if($leave){
                    $late = $this->checkLate($userAttendance->first()['user_id'],$userAttendance->first()['clock_in_time'],$attendanceSettings,$leave);
                 }else{
                     $late  = $userAttendance->first()['late'] ?? '';
                 }
                $data['status'] = 'Present';
                $data['location'] = $attendance->first()['location'];
                $data['attendance_status'] = $userAttendance->last()['attendance_status'];
                $data['attendanceIn'] = [
                    'InTime' => $userAttendance->first()['clock_in_time'] ? Carbon::parse($userAttendance->first()['clock_in_time'])
                        ->setTimezone('Asia/Kolkata')
                        ->format('H:i') : '',
                    'latitude' => $userAttendance->first()['clock_in_latitude'] ?? '',
                    'longitude' => $userAttendance->first()['clock_in_longitude'] ?? '',
                    'late' => $late,
                ];
                $data['attendanceOut'] = [
                    'OutTime' => $userAttendance->last()['clock_out_time'] ? Carbon::parse($userAttendance->last()['clock_out_time'])
                        ->setTimezone('Asia/Kolkata')
                        ->format('H:i') : '',
                    'latitude' => $userAttendance->last()['clock_out_latitude'] ?? '',
                    'longitude' => $userAttendance->last()['clock_out_longitude'] ?? '',
                ];
            }



            $formattedData[] = $data;
        }

        return ApiResponse::make('Data found successfully', $formattedData);
    }
  public function checkLate($user_id,$date,$attendanceSettings,$leave){
       $this->global = api_user()->company;
        $date = Carbon::parse($date);
        $timestamp = $date->format('Y-m-d') . ' ' . $attendanceSettings->office_start_time;
        $officeStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, $this->global->timezone);
        $officeStartTime = $officeStartTime->setTimezone('UTC');
        $lateTime = $officeStartTime->addMinutes($attendanceSettings->late_mark_duration);
        if($leave->type->type_name  == 'Short Leave' && $leave->start_time){
            $lateTime = $lateTime->addHours(2);
        }
        if($leave->duration == "half_day"  && $leave->half_day_type == "first_half"){
            $lateTime = $lateTime->addHours(4.5);
        }
        if ($date->gt($lateTime)) {
            return "yes";
        }else{
            return "no";
        }
    }



}
