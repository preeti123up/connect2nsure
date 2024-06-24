<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Helper\Reply;
use App\Models\Wishes;
use App\Models\Wish;
use App\Models\Reply as wishReply;
use App\Models\EmployeeDetails;
use App\Models\CelebratedEvent;
use Carbon\Carbon;
use Froiden\RestAPI\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Event;
use DateTime;

use Illuminate\Support\Facades\DB;

class CelebrationController extends ApiBaseController
{
    public function Celebration()
    {
      
        $todayEvents = Event::with(['files', 'attendee' => function ($query) {
            $query->where('user_id', api_user()->id)
                  ->with('user'); 
        }])->whereDate('start_date_time', '=', Carbon::today()->format('Y-m-d'))
          ->get();
          $event = [];
       
        foreach($todayEvents as $todayEvent){
          if($todayEvent->files->count()){
          $event[0]['id'] = $todayEvent->id;
          $event[0]['event_name'] = $todayEvent->event_name;
          $event[0]['label_color'] = $todayEvent->label_color;
          $event[0]['where'] = $todayEvent->where;
          $event[0]['description'] = $todayEvent->description;
          $startDateTime = new DateTime($todayEvent->start_date_time);
          $endDateTime = new DateTime($todayEvent->end_date_time);
          
          $event[0]['start_date_time'] = $startDateTime->format('d M, Y h:i A');
          $event[0]['end_date_time'] = $endDateTime->format('d M, Y h:i A');
          $event[0]['file'] = $todayEvent->files[0]->file_url;
        }
        }
      
        $todayBirthday = EmployeeDetails::where([
            [DB::raw('MONTH(date_of_birth)'), Carbon::today()->format('m')],
            [DB::raw('DAY(date_of_birth)'), Carbon::today()->format('d')],
        ])
        ->join('users', 'employee_details.user_id', '=', 'users.id')
        ->join('designations', 'employee_details.designation_id', '=', 'designations.id')
        ->select([
            'employee_details.date_of_birth',
            'employee_details.company_id',
            'employee_details.user_id',
            'users.name as name',
            'users.image as image',
            'designations.name as designation_name'
        ])
       
        ->get()
        ->toArray();
          
        

       
       
        if(count($todayBirthday)){
            foreach($todayBirthday as $i => $v){
                unset($todayBirthday[$i]['company']); 
                unset($todayBirthday[$i]['department']); 
                unset($todayBirthday[$i]['upcoming_birthday']); 
                unset($todayBirthday[$i]['designation']);
                $dateOfBirth = Carbon::parse($todayBirthday[$i]['date_of_birth']);
                $todayBirthday[$i]['date_of_birth'] = $dateOfBirth->format("d-m-Y");
                $todayBirthday[$i]['date'] = $dateOfBirth->format("d F");
           
            $randomSettings = CelebratedEvent::
            join('wishes_setting','celebrated_events.celebration_type_id','wishes_setting.id')
            ->join('celebration_types','wishes_setting.type','celebration_types.id')
            ->where('celebrated_events.user_id', $todayBirthday[$i]['user_id'])
            ->where('celebrated_events.event_type', 'birthday_celebration')
            ->select(['celebrated_events.id','celebration_types.celebration_type','wishes_setting.app_image','wishes_setting.font_color','wishes_setting.rtl','wishes_setting.message'])
            ->first();
            if($randomSettings){
                 $wish = Wish::withOnly(['reply'])
    ->where('celebrated_event_id', $randomSettings->id)
    ->get();
              $todayBirthday[$i]['wishes'] = $wish;
              $todayBirthday[$i]['settings'] = $randomSettings ? $randomSettings->toArray() : null;
             $todayBirthday[$i]['image'] = $todayBirthday[$i]['image']?asset_url_local_s3('avatar/' . $todayBirthday[$i]['image'], true, 'image') : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';
             $todayBirthday[$i]['settings']['background_image'] = $todayBirthday[$i]['settings']['app_image']?asset_url_local_s3('app_image/' . $todayBirthday[$i]['settings']['app_image'], true, 'image') : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';
            }else{
                unset($todayBirthday[$i]);
            }

            
            }
           
          }
       
       

          $todayWorkAnniversary = EmployeeDetails::where([
            [DB::raw('MONTH(joining_date)'), Carbon::today()->format('m')],
            [DB::raw('DAY(joining_date)'), Carbon::today()->format('d')],
        ])
        ->join('users', 'employee_details.user_id', '=', 'users.id')
        ->join('designations', 'employee_details.designation_id', '=', 'designations.id')
        ->select([
            'employee_details.joining_date',
            'employee_details.company_id',
            'employee_details.user_id',
            'users.name as name',
            'users.image as image',
            'designations.name as designation_name'
        ])
      
        ->get()
        ->toArray();
        
        if(count($todayWorkAnniversary)){
            foreach($todayWorkAnniversary as $i => $v){
                unset($todayWorkAnniversary[$i]['company']); 
                unset($todayWorkAnniversary[$i]['department']); 
                unset($todayWorkAnniversary[$i]['upcoming_birthday']); 
                unset($todayWorkAnniversary[$i]['designation']);
                $dateOfJoining = Carbon::parse($todayWorkAnniversary[$i]['joining_date']);
                $todayWorkAnniversary[$i]['joining_date'] = $dateOfJoining->format("d-m-Y");
                $todayWorkAnniversary[$i]['date'] = $dateOfJoining->format("d F");
                $randomSettings = CelebratedEvent::
            join('wishes_setting','celebrated_events.celebration_type_id','wishes_setting.id')
            ->join('celebration_types','wishes_setting.type','celebration_types.id')
            ->where('celebrated_events.user_id', $todayWorkAnniversary[$i]['user_id'])
            ->where('celebrated_events.event_type', 'work_anniversary')
            ->select(['celebrated_events.id','celebration_types.celebration_type','wishes_setting.app_image','wishes_setting.font_color','wishes_setting.rtl','wishes_setting.message'])
            ->first();
            if($randomSettings){
                 $wish = Wish::withOnly(['reply'])
    ->where('celebrated_event_id', $randomSettings->id)
    ->get();
            $todayWorkAnniversary[$i]['wishes'] = $wish;
            
             $todayWorkAnniversary[$i]['settings'] = $randomSettings ? $randomSettings->toArray() : null;
             $todayWorkAnniversary[$i]['image'] = $todayWorkAnniversary[$i]['image']?asset_url_local_s3('avatar/' . $todayWorkAnniversary[$i]['image'], true, 'image') : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';
             $todayWorkAnniversary[$i]['settings']['background_image'] = $todayWorkAnniversary[$i]['settings']['app_image']?asset_url_local_s3('app_image/' . $todayWorkAnniversary[$i]['settings']['app_image'], true, 'image') : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';
            }else{
                 unset($todayWorkAnniversary[$i]);
            }
          
            }
          }
          
      
          $todayMarriageAnniversary = EmployeeDetails::where([
            [DB::raw('MONTH(marriage_anniversary_date)'), Carbon::today()->format('m')],
            [DB::raw('DAY(marriage_anniversary_date)'), Carbon::today()->format('d')],
        ])
        ->join('users', 'employee_details.user_id', '=', 'users.id')
        ->join('designations', 'employee_details.designation_id', '=', 'designations.id')
        ->select([
            'employee_details.marriage_anniversary_date',
            'employee_details.company_id',
            'employee_details.user_id',
            'users.name as name',
            'users.image as image',
            'designations.name as designation_name'
        ])
        ->where('users.id','!=',api_user()->id)
        ->get()
        ->toArray();
        
        if(count($todayMarriageAnniversary)){
            foreach($todayMarriageAnniversary as $i => $v){
                unset($todayMarriageAnniversary[$i]['company']); 
                unset($todayMarriageAnniversary[$i]['department']); 
                unset($todayMarriageAnniversary[$i]['upcoming_birthday']); 
                unset($todayMarriageAnniversary[$i]['designation']);
                $marriage_anniversary_date = Carbon::parse($todayMarriageAnniversary[$i]['marriage_anniversary_date']);
                $todayMarriageAnniversary[$i]['marriage_anniversary_date'] = $marriage_anniversary_date->format("d-m-Y");
                $todayMarriageAnniversary[$i]['date'] = $marriage_anniversary_date->format("d F");
                $randomSettings = CelebratedEvent::
                join('wishes_setting','celebrated_events.celebration_type_id','wishes_setting.id')
                ->join('celebration_types','wishes_setting.type','celebration_types.id')
                ->where('celebrated_events.user_id', $todayMarriageAnniversary[$i]['user_id'])
                ->where('celebrated_events.event_type', 'marriage_anniversary')
                ->select(['celebrated_events.id','celebration_types.celebration_type','wishes_setting.app_image','wishes_setting.font_color','wishes_setting.rtl','wishes_setting.message'])
                ->first();
                if($randomSettings){
           $wish = Wish::withOnly(['reply'])
    ->where('celebrated_event_id', $randomSettings->id)
    ->get();
              $todayMarriageAnniversary[$i]['wishes'] = $wish;
               $todayMarriageAnniversary[$i]['settings'] = $randomSettings ? $randomSettings->toArray() : null;
             $todayMarriageAnniversary[$i]['image'] = $todayMarriageAnniversary[$i]['image']?asset_url_local_s3('avatar/' . $todayMarriageAnniversary[$i]['image'], true, 'image') : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';
             $todayMarriageAnniversary[$i]['settings']['background_image'] = $todayMarriageAnniversary[$i]['settings']['app_image']?asset_url_local_s3('app_image/' . $todayMarriageAnniversary[$i]['settings']['app_image'], true, 'image') : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';

                }else{
                   unset($todayMarriageAnniversary[$i]); 
                }
            }
          }
        
       $data['work_anniversary'] = $todayWorkAnniversary? $todayWorkAnniversary:[];
       $data['marriage_anniversary'] = $todayMarriageAnniversary? $todayMarriageAnniversary:[];
       $data['birthday'] = $todayBirthday? $todayBirthday:[];
       $data['Event'] = $todayEvents?$event:[];
   
            return ApiResponse::make('success', $data);
    }
}

