<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use Auth;
use Carbon\Carbon;
use DateTimeZone;
use App\Helper\Reply;
use App\Models\User;
use App\Models\UserAuth;
use Yajra\DataTables\Facades\DataTables;

class BranchController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Branch';
        $this->activeSettingMenu = 'branch';

    }


    public function index()
    {

        if (\request()->ajax()) {

            $branch = Branch::where(['company_id' => company()->id])->get();


            $data = DataTables::of($branch)
                ->addColumn(
                    'action',
                    function ($row) {

                        return '<div class="task_view"> <a data-user-id="' . base64_encode($row->id) . '" class="task_view_more d-flex align-items-center justify-content-center edit-custom-field" href="javascript:;" data-id="{{ $permission->id }}" > <i class="fa fa-edit icons mr-2"></i>' . __('app.edit') . '</a> </div>
                    <div class="task_view"> <a data-user-id="' . $row->id . '" class="task_view_more d-flex align-items-center justify-content-center sa-params" href="javascript:;" data-id="{{ $permission->id }}"  >
                            <i class="fa fa-trash icons mr-2"></i> ' . __('app.delete') . ' </a> </div>';
                    }
                )
                ->rawColumns(['action'])
                ->make(true);

            return $data;
        }

        return view('branch.index', $this->data);
    }

    public function create(){

          return  view('branch.branch-modal', $this->data);
    }

    public function store(Request $request)
    {
        $data['company_id']= company()->id;
        $data['branch_name'] = $request->input('branch_name');
        $data['branch_code'] = $request->input('branch_code');
        $data['lat'] = $request->input('latitude');
        $data['long'] = $request->input('longitude');
        $data['added_by']= user()->id;
        $res = Branch::create($data);
        if($res)
        {
        $admins = User::allAdmins(company()->id);
        $this->addFaceAdmin(company(),$res->id,$admins[0]->email);
          return Reply::success('Branch Add Successfully');
        }else{
          return Reply::error(' Branch Not Add');
        }

    }

    public function edit($id)
    {

        $id = base64_decode($id);

        $this->branch = Branch::where(['id'=>$id,'company_id'=>company()->id])->first();

        return view('branch.edit-branch-modal', $this->data);
    }


    public function update(Request $request, $id){
        $id = base64_decode($id);
        $branch = Branch::find($id);

        if($branch !=null)
        {
            $branch->branch_name = $request->input('branch_name');
            $branch->branch_code = $request->input('branch_code');
            $branch->lat = $request->input('latitude');
            $branch->long = $request->input('longitude');
            $branch->save();
            return Reply::success('messages.updateSuccess');
        }else{
            return Reply::error(' Branch Not Found');
        }

    }

    public function destroy($id)
    {
        $now = Carbon::now(new DateTimeZone('Asia/Kolkata'));
        $branch = Branch::find($id);
        if($branch !=null)
        {
            $branch->delete();
            return Reply::success('messages.deleteSuccess');
        }else{
            return Reply::error(' Branch Not Found');
        }

    }
        function generateUniqueEmail($baseEmail) {
        $email = $baseEmail;
        if (!User::where('email', $email)->exists()) {
            return $email;
        }
        $parts = explode('@', $baseEmail);
        $username = $parts[0];
        $domain = $parts[1];
        $counter = 1;
        while (User::where('email', $email)->exists()) {
            $email = $username . str_pad($counter, 2, '0', STR_PAD_LEFT) . '@' . $domain;
            $counter++;
        }
        return $email;
    }

    public function addFaceAdmin($company,$branch_id = NULL,$email) {
        $email = $this->generateUniqueEmail($email);
        $user = new User();
        $userAuth = UserAuth::createUserAuthCredentials($email);
        $user->company_id = $company->id;
        $user->email = $email;
        $user->branch_id = $branch_id;
        $user->status = 'active';
        $user->user_auth_id = $userAuth->id;
        $user->branch_login = $this->generateBranchId();
        $user->save();
    }
    
 
    public function generateBranchId() {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomString = '';
        $length = 6; 
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        $shuffledString = str_shuffle($randomString);
        $branchId = 'BR' . $shuffledString;
        return $branchId;
    }


}
