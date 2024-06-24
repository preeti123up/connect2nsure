<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Helper\Reply;
use App\Models\CelebrationType;
use App\Models\EmployeeDetails;
use App\Models\Wish;
use App\Models\Reply as wishReply;
use App\Models\celebratedEvent;
use Carbon\Carbon;
use Froiden\RestAPI\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Froiden\RestAPI\ApiController;



class WishController extends ApiController
{
    protected $model = Wish::class;
    public function sendWish(Request $request){
    $wish = new Wish();
    $wish->celebrated_event_id = $request->event_id; 
    $wish->message = $request->message;
    $wish->wished_by = api_user()->id;
    $wish->save();
    return ApiResponse::make('Wished Successfully', $wish->id);
   }
   public function Reply(Request $request){
   $wishReply = new wishReply();
   $wishReply->message = $request->message;
   $wishReply->wish_id = $request->wish_id;
   $wishReply->replied_by = api_user()->id;
   $wishReply->save();
   return ApiResponse::make('Replied Successfully', $wishReply->id);
   }
}