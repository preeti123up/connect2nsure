<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use Auth;
use Carbon\Carbon;
use DateTimeZone;
use App\Helper\Reply;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Models\AssignBranch;
use Illuminate\Support\Facades\DB;

class BranchAssignController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Assign Branch';
        $this->activeSettingMenu = 'Assign Branch';

    }


    public function index()
    {
        if (\request()->ajax()) {
            $branches = AssignBranch::join('users', 'assign_branch.user_id', '=', 'users.id')
            ->leftJoin('branches', 'assign_branch.branch_id', '=', 'branches.id')
              ->where('branches.company_id',user()->company_id)
            ->select('branches.branch_name','assign_branch.branch_id',DB::raw('COUNT(assign_branch.id) as total_assignments'))
            ->groupBy('branches.branch_name')
            ->get();

            $i=1;
            $data = DataTables::of($branches)
            ->addColumn('s_no',  function () use (&$i) {
                return $i++;
              })
            ->addColumn(
                'employees',
                function ($row) {
                    return '
                    <div class="task_view">
                    <a data-user-id="' .$row->branch_id. '" class="task_view_more d-flex align-items-center justify-content-center list-custom-field" href="javascript:;" data-id="{{ $permission->id }}" >
                    <button class="btn  btn-sm">'.$row->total_assignments. '</button>

                        </a>
                    </div>';
                }
            )

                ->rawColumns(['employees','s_no'])
                ->make(true);

            return $data;
        }
        return view('assignBranch.index', $this->data);
    }

    public function create(){
        $this->branch = Branch::where(['company_id' => company()->id])->get();
         $branchId=Branch::where(['company_id' => company()->id])->get()->pluck('id')->toArray();
        $assignBranch=AssignBranch::whereIn('branch_id', $branchId)->get()->pluck('user_id')->toArray();
        $this->user = User::where(['company_id' => company()->id,'branch_id'=>null])->whereNotIn('id',$assignBranch)->get();       
        return  view('assignBranch.branch-modal', $this->data);
    }

    public function store(Request $request)
    { 
        $branchId = $request->branch;
        $userIds = $request->assign;
        $success=true;
        foreach ($userIds as $userId) {
            $data = new AssignBranch();
            $data->branch_id = $branchId;
            $data->user_id = $userId;
            $success = $success && $data->save();
        }
        if($success)
        {
          return Reply::success('Branch Assign Successfully');
        }else{
          return Reply::error(' Branch Not Add');
        }

    }

    
    public function list($id)
    {
        
        $this->branches = AssignBranch::join('users', 'assign_branch.user_id', '=', 'users.id')
        ->select('users.name','assign_branch.id')
        ->where('assign_branch.branch_id',$id)
        ->get();
        return view('assignBranch.list-branch-modal', $this->data);
    }
    public function destroy($id)
    {
        $string = $id;
        $idArray = explode(',', $string);
        AssignBranch::whereIn('id',$idArray)->delete();
        return Reply::success('messages.deleteSuccess');

    }


}
