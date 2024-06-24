<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Models\OnboardScreens;
use Froiden\RestAPI\ApiResponse;
use Illuminate\Support\Facades\DB;


class OnboardScreenController extends ApiBaseController
{
    
    public function index(){
        $baseUrl='https://www.vetanwala.com/user-uploads/front/onboard-screen';
        $data = DB::table('onboard_screens')
        ->select(DB::raw("*, CONCAT('$baseUrl', '/', image) AS image_url"))
        ->get();         
        return ApiResponse::make('Data Found successfully', [$data]);

    }

     
}
