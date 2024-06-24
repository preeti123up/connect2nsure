<?php

namespace App\Http\Controllers;

use App\Models\CelebrationType;
use App\Models\AssetDevices;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use DateTimeZone;
use App\Helper\Reply;
use App\Http\Requests\Wishes\CreateRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Helper\Files;


class DevicesController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('Devices');
        $this->activeSettingMenu = 'wishes';
    }


    public function index()
    {
        
      
        if (\request()->ajax()) {
            $this->wishes = AssetDevices::where('company_id',user()->company_id)->get();
      
            $i=1;
            $this->data = DataTables::of($this->wishes)
            ->addColumn(
                'id',
                function ($row) use (&$i){
                    return $i++;
                }
            )
            ->addColumn(
                'name',
                function ($row){
                    return $row->name;
                }
            ) ->addColumn(
                    'action',
                    function ($row) {

                    $action = '<button class="btn"><a  class="dropdown-item  delete-field"  data-device-id="' . $row->id . '" >' . __('app.delete') . '</a></button>';
    
                  return $action;
                    }
                )
                ->rawColumns(['action', 'namwe','id'])
                ->make(true);
    
            return $this->data;
        }
    
        return view('asset.ajax.asset-type', $this->data);
    }

    public function create()
    {
        // abort_403(!in_array('admin', user_roles()));
        return view('asset.ajax.asset-type', $this->data);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
            AssetDevices::create([
              'name'=>$request->input('name'),
              'company_id'=>user()->company_id,
              'created_by'=>user()->id
            ]);
            return Reply::success('Devices Added Successfully');
       
    }


    public function destroy($id)
    {
        $Asset= AssetDevices::find($id);
        if ($Asset != null) {
            $Asset->delete();
            $redirectUrl = route('asset.index');
            return Reply::success('Deleted Successfully');
        } else {
            return Reply::error(' Devices Not Found');
        }
    }
}
