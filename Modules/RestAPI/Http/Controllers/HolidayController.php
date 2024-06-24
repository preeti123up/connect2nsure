<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Helper\Reply;
use Carbon\Carbon;
use Froiden\RestAPI\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\RestAPI\Entities\Holiday;
use Modules\RestAPI\Http\Requests\Holiday\CreateRequest;
use Modules\RestAPI\Http\Requests\Holiday\DeleteRequest;
use Modules\RestAPI\Http\Requests\Holiday\IndexRequest;
use Modules\RestAPI\Http\Requests\Holiday\ShowRequest;
use Modules\RestAPI\Http\Requests\Holiday\UpdateRequest;

class HolidayController extends ApiBaseController
{
    protected $model = Holiday::class;

    protected $indexRequest = IndexRequest::class;

    protected $storeRequest = CreateRequest::class;

    protected $updateRequest = UpdateRequest::class;

    protected $showRequest = ShowRequest::class;

    protected $deleteRequest = DeleteRequest::class;

    public function Holiday(Request $request){
        $query = Holiday::query();
        $hol = [];
        // $this->year = Carbon::now()->format('Y');
        // $this->month = Carbon::now()->format('m');
        // if($request->year){
        //     $this->year = $request->year;
        // }
        // if($request->month){
        //     $this->month = $request->month;
        // }
        // $query->where(DB::raw('Year(holidays.date)'), '=', $this->year);
        // $query->Where(DB::raw('Month(holidays.date)'), '=', $this->month);
       
        $this->holidays = $query->select(['id','company_id','occassion','date','holiday_type'])->orderBy(DB::raw('Date(holidays.date)'), 'ASC')->get();
      
        foreach ($this->holidays  as $key => $holiday) {
            $holiday->day = $holiday->date->format('l');
            $date =  $holiday->date->format('Y-m-d');
            unset($holiday->date);
            unset($holiday->event_id);
            $holiday->Date =  $date;
            $hol[]= $holiday;   
        }
        
        if($hol){
            return ApiResponse::make('Data found successfully',$hol);
        }
          return Reply::error(__('Data Not Found'));
    }
}
