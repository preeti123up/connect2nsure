<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\EmployeeShiftSchedule;
use App\Models\User;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Holiday;
use Carbon\Carbon;
use DateTime;
use DatePeriod;
use DateInterval;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\EmployeeDetails;
use App\Models\AssignBranch;
class AttendanceExport implements FromView, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $export_request;


     public function __construct($export_request)
    {

        $this->export_request = $export_request;

    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(13);

            },
        ];

    }


    public function view(): View
    {
        $ex_req = $this->export_request;
        $compnay = company();
        $data = [];
        $ex_req['month'] = (int)$ex_req['month'];
        $ex_req['year'] = (int)$ex_req['year'];
        $ex_req['userID'] = (int)$ex_req['userID'];
        $ex_req['department'] = (int)$ex_req['department'];
        $ex_req['designation'] = (int)$ex_req['designation'];
        $ex_req['branch'] = (int)$ex_req['branch'];
       
     
     if($ex_req['date']){
        $ex_req['date'] = date("j", strtotime($ex_req['date']));
     }
    
        $employees = DB::table('users')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->join('employee_details', 'employee_details.user_id', '=', 'users.id')
            ->join('teams', 'teams.id', '=', 'employee_details.department_id')
            ->join('designations', 'designations.id', '=', 'employee_details.designation_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.mobile',
                'teams.team_name', 
                'designations.name as designation_name',
                'employee_details.department_id',
                'employee_details.designation_id',
                'employee_details.joining_date'
            )
            ->where('roles.name', '=', 'employee')
            ->where('users.status', '=', 'active')
            ->where('users.company_id','=',$compnay->id)
            ->where('teams.team_name', '!=','ADMIN');
        
        if ($ex_req['userID']) {
            $employees = $employees->where('users.id', $ex_req['userID']);
        }
        
        // Check if branch id
        if ($ex_req['branch']) {
            $assign = AssignBranch::where('branch_id', $ex_req['branch'])->pluck('user_id')->toArray();
            $employees = $employees->whereIn('users.id', $assign);
        }
        
        // Check if designation
        if ($ex_req['designation']) {
            $employees = $employees->where('employee_details.designation_id', $ex_req['designation']);
        }
        
        // Check if department
        if ($ex_req['department']) {
            $employees = $employees->where('employee_details.department_id', $ex_req['department']);
        }
        
        $employees = $employees->groupBy('users.id')->get();

        $this->currentMonth = Carbon::parse('01-' . $ex_req['month'] . '-' . $ex_req['year']);
        $this->daysInMonth = Carbon::parse('01-' . $ex_req['month'] . '-' . $ex_req['year'])->daysInMonth;
        $month = Carbon::parse('01-' . $ex_req['month'] . '-' . $ex_req['year'])->lastOfMonth();
        $now = Carbon::now()->timezone("Asia/Kolkata");
        $requestedDate = Carbon::parse(Carbon::parse('01-' . $ex_req['month'] . '-' . $ex_req['year']))->endOfMonth();

        foreach($employees as $i => $v){
            $dataTillToday = array_fill(1, $this->daysInMonth, 'A');
            if($requestedDate->isPast()){
                $dataTillToday = array_fill(1, $this->daysInMonth, 'A');
            }
            else{
                $dataTillToday = array_fill(1, $now->copy()->format('d'), 'A');
            }

            if (($now->copy()->format('d') != $this->daysInMonth) && !$requestedDate->isPast()) {
                $dataFromTomorrow = array_fill($now->copy()->addDay()->format('d'), ($this->daysInMonth - $now->copy()->format('d')), '-');
            } else {
                if($this->daysInMonth < $now->copy()->format('d')){
                    $dataFromTomorrow = array_fill($month->copy()->addDay()->format('d'), (0), 'A');
                }
                else{
                    $dataFromTomorrow = array_fill($month->copy()->addDay()->format('d'), ($this->daysInMonth - $now->copy()->format('d')), 'A');
                }
            }
            $data[$v->id.'#'.$v->name] = array_replace($dataTillToday, $dataFromTomorrow);
        }
     
      
       
     foreach ($data as $i => $employee) {
        $dataArray = explode('#', $i);
        $employe_details = EmployeeDetails::where('user_id',$dataArray[0])->first();
        if($employe_details == null){
           
        }
        $joiningDate = Carbon::parse($employe_details->joining_date);
        $total_hour = 0;
        $total_extra_hour = 0;
        $total_extra_working_days = 0;
        $total_minute = 0;
        $total_present = 0;
        $total_absent = 0;
        $total_leave = 0;
        $total_late = 0;
        $total_week_of = 0;
        $total_holiday = 0;
        $total_working_days = 0;
        $extra_working_hours =0;
        $total_missed_clockout = 0;
        $leaveTypes = LeaveType::with(['leaves' => function ($query) use ($ex_req, $dataArray) {
            $query->where(DB::raw('month(leave_date)'), $ex_req['month'])
                ->where(DB::raw('year(leave_date)'), $ex_req['year'])
                ->where('user_id', $dataArray[0])
                ->where('status', 'approved');
        }])->get();
        
        $employeeDetails = [];
       
        foreach ($leaveTypes as $leave) {
            $totalDuration = $leave->leaves->sum(function ($singleLeave) {
                return ($singleLeave->duration === 'half_day') ? 0.5 : 1;
            });
            $leaveName = $leave->type_name;
            if (!empty($leaveName)) {
                if (!array_key_exists($leaveName, $employeeDetails)) {
                    $employeeDetails[$leaveName] = 0; 
                }
                $employeeDetails[$leaveName] += $totalDuration;
            }
        }
 foreach ($employee as $day => $status) {
    $attendences_details = [];
    $attendences_details['status'] = 0; 
    $attendences_details['clockIn'] = 0;
    $attendences_details['clockOut'] = 0; 
    $attendences_details['late'] = 0;
    $attendences_details['color'] = "black";
    $attendences_details['leave'] = [];
   
   
 $today_date = Carbon::parse($ex_req['year'].'-' . $ex_req['month'] . '-' .$day)->format('Y-m-d');
 $today_day = Carbon::parse($ex_req['year'].'-' . $ex_req['month'] . '-' .$day)->dayOfWeek;
 $attendanceSettings = AttendanceSetting::first()->shift;
 $todayShift = EmployeeShiftSchedule::with('shift')->whereDate('date', $today_date)->where('user_id',$dataArray[0])
 ->first();
 if($todayShift){
    $shiftOpenDays = array_map('intval',  $todayShift->shift->office_open_days);
    if (!in_array( $today_day , $shiftOpenDays)) {
        $attendences_details['status'] = "Week Off";
        $total_week_of++;
        $attendences_details['color'] = "purple";          
      }else{
        $total_working_days++;
      }
 }else{
    $officeOpenDays = array_map('intval', $attendanceSettings->office_open_days);
    if (!in_array( $today_day , $officeOpenDays)) {
       $attendences_details['status'] = "Week Off";
       $total_week_of++;
       $attendences_details['color'] = "purple";          
     }
     else{
        $total_working_days++;
      }
 }

    $today_holiday = Holiday::where(DB::raw('DATE(date)'), $today_date)
            ->where('holiday_type','Gazetted')
            ->first();
            if($today_holiday !== NULL){
                $attendences_details['status'] = "H";
                $total_holiday++;
                $attendences_details['color'] = "purple";
             }
    $today_leave = Leave::with('type')->where('user_id', $dataArray['0'])->where(DB::raw('DATE(leave_date)'), $today_date)->where('status', 'approved')->first();
            if($today_leave){
                $attendences_details['color'] = $today_leave->type->color;
                if($today_leave->type->type_name === "Short Leave" || $today_leave->duration === "half_day"){
         if($today_leave->type->type_name === "Short Leave"){
                        $attendences_details['leave']['type'] = "SL";
                    }else{
                        $attendences_details['leave']['type'] = ' ('.$today_leave->type->type_name . ")";
                        $total_leave  = $total_leave + 0.5;
                    }
                  $attendences_details['leave']['date'] = $today_leave->date;
                  $attendences_details['leave']['duration'] = $today_leave->duration;
                  $attendences_details['leave']['half_day_type'] = $today_leave->half_day_type;
                  $attendences_details['leave']['start_time'] = $today_leave->start_time;
                  $attendences_details['leave']['end_time'] = $today_leave->end_time;
                  
                }else{
                    $attendences_details['status'] = "L";
                    $attendences_details['leave']['date'] = $today_leave->date;
                    $attendences_details['leave']['duration'] = $today_leave->duration;
                    $attendences_details['leave']['half_day_type'] = $today_leave->half_day_type;
                    $attendences_details['leave']['start_time'] = $today_leave->start_time;
                    $attendences_details['leave']['end_time'] = $today_leave->end_time;
                    $attendences_details['leave']['type'] = $today_leave->type->type_name;
                    $total_leave  = $total_leave + 1;
                }
            }
         
            $attendance = Attendance::where('user_id', $dataArray['0'])->where(DB::raw('DATE(clock_in_time)'),$today_date)
            ->select(['id', 'clock_in_latitude','attendance_status', 'clock_in_longitude', 'clock_in_time', 'clock_out_time', 'clock_out_latitude', 'clock_out_longitude','late','location','working_from','half_day','status'])
            ->get();
            if($attendance->count()){
               
                if(!($attendance->first()['location']) || $attendance->first()['status'] == "pending"){
                    $attendences_details['text-color'] = "black";
                }else{
                    $attendences_details['text-color'] ="blue";
                }
                $clockIn = Carbon::parse($attendance->first()['clock_in_time'])->setTimezone('Asia/Kolkata');
                $clockOut = $attendance->last()['clock_out_time']
                    ? Carbon::parse($attendance->last()['clock_out_time'])->setTimezone('Asia/Kolkata')
                    : null;
                $attendences_details['clockIn'] = $clockIn->format('g:i a');
                $attendences_details['clockOut'] = $clockOut ? $clockOut->format('g:i a') : "N/A";
                 if($today_leave){
                    $attendences_details['late'] = $this->checkLate($attendance->first()['user_id'],$attendance->first()['clock_in_time'],$attendanceSettings,$today_leave);
                   }else{
                    $attendences_details['late']  = $attendance->first()['late'];
                   }
                    if($attendences_details['late'] !== "no"){
                    $total_late++;
                   }
                   $hoursWorked = 0;
                if ($clockOut) {
                    if((int)$ex_req['date']){
                        if($day == (int)$ex_req['date']){
                            $hoursWorked = $clockOut->diffInMinutes($clockIn); 
                        }
                      }else{
                        $hoursWorked = $clockOut->diffInMinutes($clockIn);
                        $extraMinutes = 0;
                        if($attendences_details['status'] == "Week Off" || $attendences_details['status'] == "H"){
                            $extraMinutes  =  $clockOut->diffInMinutes($clockIn);
                            $extraHours  =  floor($extraMinutes / 60); 
                            if($extraHours < 5){
                                // $extraMinutes = 0;
                                $total_extra_working_days += 0.5;
                             }else{
                                $total_extra_working_days++;
                             }
                        }
                      }
                      $total_hour += $hoursWorked;
                      $total_extra_hour += $extraMinutes;
                
                }else{
                    $total_missed_clockout++;
                }
               
                $attendences_details['hours_worked'] = $hoursWorked;
                if(($hoursWorked < 4 )
                && count($attendences_details['leave'])
                && $attendences_details['leave']['duration'] == "single"  
                && !isset($attendences_details['leave']['half_day_type'])
                && !isset($attendences_details['leave']['start_time'])
                && !isset($attendences_details['leave']['end_time'] ))
                {
                    $attendences_details['status'] ="L";
                }else{
                    $attendences_details['status'] ="P";
                    if ($attendance->first()['half_day'] == "yes") {
                        $total_present += 0.5; 
                    } else {
                        $total_present++; 
                    }
                }
            }else{
                if(!($attendences_details['status']) && $data[$i][$day] !== '-'){
                    if($joiningDate->greaterThan(Carbon::parse($day.'-' . $ex_req['month'] . '-' . $ex_req['year']))){
                        $attendences_details['status'] ="NR";
                        $attendences_details['color'] = "#A2A2D0";
                    }else{
                     $attendences_details['status'] ="A";
                     $attendences_details['color'] = "red";
                     $total_absent++;
                      
                    }
                   
                }
                
            }
            
            if($data[$i][$day] !== '-'){
                $data[$i][$day] = $attendences_details;
            }else{
              if(Carbon::now()->month == $ex_req['month']){
                  unset($data[$i][$day]);
                 }
             }
          if($day !== (int)$ex_req['date'] && (int)$ex_req['date']){
            unset($data[$i][$day]);
          }
          
      }
     

     


    // Convert minutes to hours
    $additional_hours = (int)$total_hour / 60; 
$hours = floor($additional_hours); 
$minutes = (int)$total_hour % 60; // Calculate minutes using modulus operator
$working_hour = $hours . ':' . $minutes;


    $extra_worked_hours = floor((int)$total_extra_hour / 60); 
    $extra_worked_minutes = (int)$total_extra_hour % 60;
    $extra_working_hour = $extra_worked_hours . ':' . $extra_worked_minutes;
    
     
     $new_employeeDetails = [
        'total_hours_worked' => $working_hour,
        'Total Days' => $this->daysInMonth,
        'Working Days' =>$total_working_days - $total_holiday,
        'Extra Working Days' => $total_extra_working_days,
        'Week Off' => $total_week_of,
        'Holiday' => $total_holiday,
        'Present' => $total_present,
        'Absent' => $total_absent,
        'Late' => $total_late,
      
        // 'Missed Punch Out' =>$total_missed_clockout,
    ];
    $employeeDetails = array_merge($new_employeeDetails,$employeeDetails);
    $data[$i][] = $employeeDetails;
    }
 
    
    $company_name = user()->company->company_name;
        return view('attendances.exportAtt', [
            'data' => $data,
            'maxDays' => $this->daysInMonth,
            'month' => $ex_req['month'],
            'year' => $ex_req['year'],
            'current_month' => Carbon::now()->month,
            'date' => $ex_req['date']?$ex_req['date']:false,
            'branch' => $ex_req['branch']?$ex_req['branch']:$company_name,
            'employeeDetailsArray' =>$employeeDetails,
        ]);
    }
    

    function getBetweenDates($startDate, $endDate) {
        $array = array();
        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($endDate);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($startDate), $interval, $realEnd);

        foreach($period as $date) {
            $array[] = $date;
        }

        return $array;
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
