<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Helper\Reply;
use App\Models\User;
use App\Models\FaceRegister;
use App\Models\FaceImage;
use App\Models\EmployeeShiftSchedule;
use App\Models\AttendanceSetting;
use App\Models\Attendance;
use App\Models\CompanyAddress;
use App\Models\AssignBranch;
use Carbon\Carbon;
use App\Helper\Files;
use Froiden\RestAPI\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Froiden\RestAPI\ApiController;

class FaceAttendanceController extends ApiController
{
   
    public function employeeList(){
        if(api_user()->branch_login !== Null){
            if(api_user()->default_branch){
                $employees = DB::table('users')
                ->select('users.id', 'users.company_id', 'users.name', 'users.email', 'users.branch_login', 'users.image')
                ->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'role_user.role_id', '=', 'roles.id')
                ->where('users.company_id', '=', api_user()->company_id)
                ->where('roles.name', '=', 'employee')
                ->whereNull('users.branch_login')
                ->whereNotIn('users.id', function ($query) {
                    $query->select('user_id')
                        ->from('assign_branch');
                })
                ->get()
                ->map(function ($employee) {
                    $employee->image_path = $employee->image ? asset_url_local_s3('avatar/' . $employee->image, true, 'image') : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($employee->email))) . '.png?s=200&d=mp';
                    unset($employee->image); 
                    $isEnrolled = FaceRegister::where('user_id', $employee->id)->exists();
                    $employee->isenroll = $isEnrolled;
                    return $employee;
                })
                ->toArray();
            

              
            }else{
                $employees = DB::table('users')
                ->select('users.id', 'users.company_id', 'users.name', 'users.email', 'users.branch_login', 'users.image')
                ->where('users.company_id', '=', api_user()->company_id)
                ->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'role_user.role_id', '=', 'roles.id')
                ->where('users.id', '!=', api_user()->id) // Specify users table for id column
                ->where('roles.name', '=', 'employee')
                ->whereIn('users.id', function ($query) {
                    $query->select('user_id')
                        ->from('assign_branch')
                        ->where('branch_id', '=', api_user()->branch_id);
                })
                ->get()
                ->map(function ($employee) {
                    $employee->image_path = $employee->image ? asset_url_local_s3('avatar/' . $employee->image, true, 'image') : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($employee->email))) . '.png?s=200&d=mp';
                    unset($employee->image); 
                    $isEnrolled = FaceRegister::where('user_id', $employee->id)->exists();
                    $employee->isenroll = $isEnrolled;
                    return $employee;
                })
                ->toArray();
            
            }
            return ApiResponse::make('data found successfully',$employees);
        }
 }  

public function FaceclockInOut(Request $request){
    $now = now();
    $this->global = api_user()->company;
    $attendanceSettings = $this->attendanceSettings($request->user_id,$now);
    $clockInCount = Attendance::getTotalUserClockIn($now->format('Y-m-d'), $request->user_id);
    $radiusSettings = AttendanceSetting::first();
    $officeStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $now->format('Y-m-d') . ' ' . $attendanceSettings->office_start_time, $this->global->timezone)
        ->setTimezone('UTC');
    $late = $this->isLate($now, $officeStartTime, $attendanceSettings->late_mark_duration);
    $halfDay = $this->isHalfDay($now, $attendanceSettings->halfday_mark_time, $this->global->timezone);

    if ($radiusSettings->radius_check == 'yes') {
        $checkRadius = $this->isWithinRadius($request,$request->user_id);
        if (!$checkRadius) {
            return ApiResponse::make(__('messages.notAnValidLocation'), []);
        }
    }
    $checkTodayAttendance = Attendance::where('user_id', $request->user_id)
    ->whereDate('clock_in_time', '=', $now->format('Y-m-d'))
    ->get();
    $countTodayAttendance = $checkTodayAttendance->count();
if ($checkTodayAttendance->isEmpty()) {
        $this->clockIn($request,$now,$late,$halfDay);
        return ApiResponse::make("Clock In Successfully", []);
} else {
    // User has checked in today
    $lastAttendance = $checkTodayAttendance->last();
    if (is_null($lastAttendance->clock_out_time)) {
        $this->clockOut($request,$attendanceSettings->clockin_in_day == $checkTodayAttendance->count() ? 2 : 1, $now);
        return ApiResponse::make("Clock Out Successfully", []);
    } else {
        if ($countTodayAttendance < $attendanceSettings->clockin_in_day) {
            $this->clockIn($request,$now,false,false);
            return ApiResponse::make("Clock In Successfully", []);
        } else {
            return ApiResponse::make("Max Clocked In Reached", []);
        }
    }
}


}
public function clockIn($request,$time,$late,$halfDay)
{
    $attendance = new Attendance;
    if ($request->hasFile('image')) {
        $userid = $request->user()->id;
        $date = now()->format('d-m-Y');
        $path = "attendance/{$userid}/{$date}";
        $attendance->clock_in_image = Files::uploadLocalOrS3($request->image, $path, 300);
    }
    $attendance->user_id = $request->user_id;
    $attendance->attendance_status = 1;
    $attendance->clock_in_time = $time;
    $attendance->clock_in_longitude = $request->longitude;
    $attendance->clock_in_latitude = $request->latitude;
    $attendance->location = true;
    $attendance->status = "approved";
    $attendance->working_from = "office";
    $attendance->late = $late ? 'yes' : 'no';
    $attendance->half_day = $halfDay ? 'yes' : 'no';
    return $attendance->save()?true:false;
}
public function clockOut($request,$attendance_status,$time)
 {
  
    $attendance = Attendance::whereDate('clock_in_time', '=', $time) // Get records from today or later
    ->whereNull('clock_out_time')
    ->first();
    if ($request->hasFile('image')) {
        $userid = $request->user()->id;
        $date = now()->format('d-m-Y');
        $path = "attendance/{$userid}/{$date}";
        $attendance->clock_out_image = Files::uploadLocalOrS3($request->image, $path, 300);
    }
$attendance->attendance_status = $attendance_status;
$attendance->clock_out_time = $time;
$attendance->clock_out_longitude = $request->longitude;
$attendance->clock_out_latitude = $request->latitude;
$attendance->save();
return true;
 }
 
public function SyncClockInOut(Request $request){

$this->global = api_user()->company;
$radiusSettings = AttendanceSetting::first();
$prevUserId = null;
$isClockIn = true; 
$now = now();

foreach ($request['time'] as $index => $time) {
    $latitude = $request['latitude'][$index];
    $longitude =  $request['longitude'][$index];
    $user_id = (int)$request['user_id'][$index];
    $time =  Carbon::createFromFormat('Y-m-d H:i:s',$request['time'][$index]);
    $date = Carbon::createFromFormat('Y-m-d H:i:s',$request['time'][$index])->startOfDay();
    $image = $request['image'][$index];
    $user_deatils = User::find($user_id);
    if (isset($request['image'][$index])) {
        $userid = $user_id;
        $date = $date->format('d-m-Y');
        $path = "attendance/{$userid}/{$date}";
        $image = Files::uploadLocalOrS3($request->image, $path, 300);
    }


    $attendanceSettings = $this->attendanceSettings($user_id,$date);
    $clockInCount = Attendance::getTotalUserClockIn($date,$user_id);
    $radiusSettings = AttendanceSetting::first();
    $officeStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $now->format('Y-m-d') . ' ' . $attendanceSettings->office_start_time, $this->global->timezone)
    ->setTimezone('UTC');

$late = $this->isLate($time, $officeStartTime, $attendanceSettings->late_mark_duration);

//if late and leave applied
$leave = Leave::where('leave_date',$date)
->first();

$halfDay = $this->isHalfDay($time, $attendanceSettings->halfday_mark_time, $this->global->timezone);

    if ($prevUserId !== $user_id  || !$isClockIn) {
        //check radius
        if ($radiusSettings->radius_check == 'yes') {
            $checkRadius = $this->isWithinRadius($request,$user_id);
            if($checkRadius){
                $location = true;
                $working_from = "Office";
                $status = "Approved";
            }else if($user_deatils->ood == "enable"){
                $location = false;
                $working_from = "Outside";
                $status = "Pending";
            }else{
                $location = false;
                $working_from = "Outside";
                $status = "Pending";
            }
        }else{
            $location = true;
            $working_from = "Office";
            $status = "Approved";
        }

        //check late

        $attendance = new Attendance();
        $attendance->clock_in_time = $time;
        $attendance->clock_in_latitude = $latitude;
        $attendance->clock_in_longitude = $longitude;
        $attendance->user_id = $user_id;
        $attendance->clock_in_image = $image;
        if(!$clockInCount){
            $attendance_location = $location;
            $attendance_working_from = $working_from;
            $attendance->status = $status;
            $attendance->late = $late ? 'yes' : 'no';
            $attendance->half_day = $halfDay ? 'yes' : 'no';
        }
        $attendance->save();
        $isClockIn = true;
    } else {
        $prevAttendance = Attendance::where('user_id', $user_id )
            ->whereNull('clock_out_time')
            ->latest('clock_in_time')
            ->first();
        if ($prevAttendance) {
            $prevAttendance->clock_out_time = $time;
            $prevAttendance->clock_out_latitude = $latitude;
            $prevAttendance->clock_out_longitude = $longitude;
            $attendance->clock_out_image = $image;
            $prevAttendance->save();
        }
        $isClockIn = false;
    }
    $prevUserId = $user_id;
}

}

public function FaceRegister(Request $request)
{
    $existingUser = FaceRegister::where('user_id', $request->user_id)->first();
    if ($existingUser) {
        return ApiResponse::make('User already registered',[]);
    }
    $user = new FaceRegister();
    $user->user_id = $request->user_id;
    $user->added_by = api_user()->id;
    $user->save();
    foreach($request->image as  $value) {
        $image = new FaceImage();
        $image->image = $value;
        $image->face_register_id = $user->id;
        $image->save();
    }
    return ApiResponse::make('User registered successfully', $user);
}

public function FaceUpdate(Request $request){
   $user = FaceRegister::where('id',$request->id)->first();
   $user->image = $request->image;
   $user->save();
   return ApiResponse::make('User Updated successfully',$user);
}

public function isHalfDay($now, $halfDayMarkTime, $timezone) {
    if ($halfDayMarkTime) {
        $halfDayTimestamp = Carbon::createFromFormat('Y-m-d H:i:s', $now->format('Y-m-d') . ' ' . $halfDayMarkTime, $timezone)
            ->setTimezone('UTC')
            ->timestamp;
        return $now->timestamp > $halfDayTimestamp;
    }
    return false;
}

public function isLate($now, $officeStartTime, $lateMarkDuration) {
    $lateTime = $officeStartTime->addMinutes($lateMarkDuration);
    return $now->gt($lateTime);
}

public function attendanceSettings($user_id,$date){
    $attendanceSettings = EmployeeShiftSchedule::where('user_id',$user_id)
        ->whereDate('date', $date)
        ->first(); 
        if ($attendanceSettings) {
            $attendanceSettings = $attendanceSettings->shift; 
        } else {
            $attendanceSettings = AttendanceSetting::first();
            if ($attendanceSettings) {
                $attendanceSettings= $attendanceSettings->shift;
            } else {
                $attendanceSettings = api_user()->company->attendanceSetting;
            }
        }
       return  $attendanceSettings;

}
public function isWithinRadius($request,$user_id)
    {
        $attendanceSettings = AttendanceSetting::first();
        $radius = $attendanceSettings->radius;
        $currentLatitude = $request->latitude;
        $currentLongitude = $request->longitude;
        $location = CompanyAddress::where('company_id',api_user()->company_id)->first();
        $apiCompanyId =  api_user()->company_id;
        $branch = AssignBranch::with(['branch' => function($query) use ($apiCompanyId) {
            $query->where('company_id', $apiCompanyId);
        }])->where('user_id',$user_id)->first();
       
         if($branch !== NULL){
              $latFrom = deg2rad($branch->branch->lat);
            $lonFrom = deg2rad($branch->branch->long);
         }else{
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
    public function enrollEmployeeList(){
        if(api_user()->branch_login !== Null){
            if(api_user()->default_branch){
                $employees = DB::table('users')
                ->select('users.id','face_registers.id as face_id')
                ->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'role_user.role_id', '=', 'roles.id')
                ->join('face_registers', 'users.id', '=', 'face_registers.user_id')
                ->where('users.company_id', '=', api_user()->company_id)
                ->where('roles.name', '=', 'employee')
                ->whereNull('users.branch_login')
                ->whereNotIn('users.id', function ($query) {
                    $query->select('user_id')
                        ->from('assign_branch');
                })
                ->get()
                ->map(function ($employee) {
                    $images =  FaceImage::where('face_register_id',$employee->face_id)->pluck('image');
                    $employee->image = $images;
                    return $employee;
                })
                ->toArray();
            }else{
                $employees = DB::table('users')
                ->select('users.id','face_registers.id as face_id')
                ->where('users.company_id', '=', api_user()->company_id)
                ->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'role_user.role_id', '=', 'roles.id')
                ->join('face_registers', 'users.id', '=', 'face_registers.user_id')
                ->where('users.id', '!=', api_user()->id)
                ->where('roles.name', '=', 'employee')
                ->whereIn('users.id', function ($query) {
                    $query->select('user_id')
                        ->from('assign_branch')
                        ->where('branch_id', '=', api_user()->branch_id);
                })
                ->get()
                ->map(function ($employee) {
                    $images =  FaceImage::where('face_register_id',$employee->face_id)->pluck('image');
                    $employee->image = $images;
                    return $employee;
                })
                ->toArray();
            }
            return ApiResponse::make('data found successfully',$employees);
        }
 }  
}
