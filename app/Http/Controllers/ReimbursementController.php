<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\Designation;
use App\Models\EmployeeDetails;
use Illuminate\Http\Request;
use App\DataTables\ReimbursementDataTable;
use App\Http\Requests\Designation\StoreRequest;
use App\Http\Requests\Designation\UpdateRequest;
use App\Exports\DesignationSheetExport;
use App\Jobs\ImportDesignationJob;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DesignationImport;
use App\Traits\DepartmentImportExcel;
use App\Http\Requests\Admin\Employee\ImportRequest;
use App\Http\Requests\Admin\Employee\ImportProcessRequest;
use App\Models\Reimbursement;
use App\Helper\Files;
use App\Models\LeaveFile;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;


class ReimbursementController extends AccountBaseController
{
    use DepartmentImportExcel;
    public $arr = [];

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('Reimbursement');
       
    }
    public function index(ReimbursementDataTable $dataTable)
    {
      
            $viewReimbursementPermission = user()->permission('view_reimbursement');
            abort_403(!(
            $viewReimbursementPermission  == 'all' 
            || ($viewReimbursementPermission  == 'owned') 
            || (in_array('admin', user_roles())) 
             ));
            return $dataTable->render('reimbursement.index', $this->data);
      
    }
  

    public function create()
    {
        $this->designations = Designation::all();
        $this->user=User::allEmployees();
        if (request()->ajax()) {
            $html = view('reimbursement.ajax.create', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }
        

        $this->view = 'reimbursement.ajax.create';

        return view('reimbursement.create', $this->data);
    }

    // /**
    //  * @param StoreRequest $request
    //  * @return array
    //  */
  public function store(Request $request)
{
  
    $request->validate([
        'date_expense' => 'required|date_format:d-m-Y',
         'expense' => 'required',
          'payment' => 'required',
           'amount' => 'required',
            'purpose' => 'required',
             'user_id'=>'required',

      
    ]);


    $uniqueKey = uniqid();
    $reimbursement = new Reimbursement;
    $reimbursement->user_id = $request->user_id;
    $reimbursement->company_id = user()->company_id;
    $reimbursement->expense_type = $request->expense;
    $reimbursement->payment_type = $request->payment;
    $reimbursement->date_of_expense = Carbon::createFromFormat('d-m-Y', $request->date_expense);
    $reimbursement->amount = $request->amount;
    $reimbursement->purpose = $request->purpose;
    $reimbursement->file = $request->image !== null ? Files::uploadLocalOrS3($request->image, LeaveFile::FILE_PATH) : null;
    $reimbursement->uniqueId = $uniqueKey;
    $reimbursement->save();

    return Reply::success(__('messages.recordSaved'));
}

   

    public function edit($id)
    {
        
        $this->designation = Designation::findOrFail($id);

        $designations = Designation::where('id', '!=', $this->designation->id)->get();

        $childDesignations = $designations->where('parent_id', $this->designation->id)->pluck('id')->toArray();

        $designations = $designations->where('parent_id', '!=', $this->designation->id);
        $this->user=User::allEmployees();

        // remove child designations
        $this->designations = $designations->filter(function ($value, $key) use ($childDesignations) {
            return !in_array($value->parent_id, $childDesignations);
        });


        if (request()->ajax())
        {
            $html = view('reimbursement.ajax.edit', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'reimbursement.ajax.edit';
        return view('reimbursement.create', $this->data);

    }

  

    public function list($id,$uniqueId){
        $this->reimbursement=Reimbursement::join('users', 'reimbursement.user_id', '=', 'users.id')   
        ->where('reimbursement.uniqueid',$uniqueId)
        ->select('reimbursement.*','users.name as name')
        ->get();
       return view('reimbursement.ajax.list',$this->data);

    }
    public function changeStatus(Request $request,$id)
    {   
            if( $request->action=='already paid'){
            Reimbursement::where('uniqueId', $id)->update([
                'already_paid' => true
            ]);           
         return Reply::success(__('Status Updated successfully'));
        }else{
            Reimbursement::where('uniqueId', $id)->update(['status' => $request->action]);
            return Reply::success(__('Status Updated successfully'));
        }
       
    }
    

}
