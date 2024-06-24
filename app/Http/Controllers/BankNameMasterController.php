<?php

namespace App\Http\Controllers;

use App\Models\BankNameMasters;
use Illuminate\Http\Request;
use App\Helper\Reply;
use Yajra\DataTables\Facades\DataTables;


class BankNameMasterController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('Bank Name');
        $this->activeSettingMenu = 'bank_name';
    }


    public function index()
    {
        
      
        if (\request()->ajax()) {
            $this->wishes = BankNameMasters::where('company_id',user()->company_id)->get();
      
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
    
        return view('employees.ajax.bank-name-master', $this->data);
    }

    public function create()
    {
        // abort_403(!in_array('admin', user_roles()));
        return view('employees.ajax.bank-name-master', $this->data);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $data=BankNameMasters::create([
              'name'=>$request->input('name'),
              'company_id'=>user()->company_id,
              'created_by'=>user()->id
            ]);
            return Reply::successWithData('Bank Name Added Successfully',['data'=>$data]);
       
    }


    public function destroy($id)
    {
        $Asset= BankNameMasters::find($id);
        if ($Asset != null) {
            $Asset->delete();
            return Reply::success('Deleted Successfully');
        } else {
            return Reply::error(' Devices Not Found');
        }
    }
}
