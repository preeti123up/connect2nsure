<?php

namespace Modules\RestAPI\Http\Controllers;
use Froiden\RestAPI\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Asset;
use App\Models\LendAsset;
 use Carbon\Carbon;

class AssetController extends ApiBaseController
{
    protected $model = Asset::class;
  
  

public function asset() {
    $assets = Asset::leftJoin('lend_assets', 'assets.id', '=', 'lend_assets.asset_id')
        ->leftJoin('users', 'lend_assets.user_id', '=', 'users.id')
        ->leftJoin('asset_devices', 'assets.asset_type', '=', 'asset_devices.id')
        ->where([
            'assets.company_id' => api_user()->company_id,
            'lend_assets.user_id' => api_user()->id
        ])
        ->select(
            'lend_assets.id as asset_id',
            'assets.asset_name',
            'assets.serial_number',
            'assets.description',
            'asset_devices.name as asset_device_name',
            DB::raw("DATE_FORMAT(lend_assets.given_date, '%e %M %Y') as given_date"),
            DB::raw("DATE_FORMAT(lend_assets.estimated_return_date, '%e %M %Y') as estimated_return_date"),
            'lend_assets.notes',
            DB::raw("DATE_FORMAT(lend_assets.date_of_return, '%e %M %Y') as date_of_return"),
            'lend_assets.admin_status',
            'lend_assets.lend_status as lend_status',
            'users.name as lend_user_name'
        )
        ->get()
        ->toArray();

    if (count($assets) > 0) {
        return response()->json(['message' => 'Data Found Successfully', 'data' => $assets]);
    } else {
        return response()->json(['message' => 'No Data found']);
    }
}


    public function returnAsset(Request $request,$id){
        $lend = LendAsset::where('id', (int)$id)
        ->where('user_id', api_user()->id)
        ->first();        
        $lend->date_of_return=now();
        $lend->lend_status="Returned";
        $lend->return_by=api_user()->id;
        $lend->admin_status='Pending';
        $lend->notes = $request->note;
        $lend->save();
        $asset = Asset::where('id',$lend->asset_id)->first();
                $asset->status = 'Returned';
                $asset->save();
        return ApiResponse::make('Returned Successfully');
    }

}
