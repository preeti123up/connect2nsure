<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\Designation;
use App\Models\EmployeeDetails;
use Illuminate\Http\Request;
use App\DataTables\InterviewApplicationDatatable;
use App\Http\Requests\Designation\StoreRequest;
use App\Http\Requests\Designation\UpdateRequest;
use App\Exports\DesignationSheetExport;
use App\Jobs\ImportDesignationJob;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DesignationImport;
use App\Traits\DepartmentImportExcel;
use App\Http\Requests\Admin\Employee\ImportRequest;
use App\Http\Requests\Admin\Employee\ImportProcessRequest;
use App\Models\Asset;
use App\Models\User;
use App\Models\ApplyJob;
use App\Models\AssignInterview;
use App\Models\InterviewLevel;

class InterviewApplicationController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('Interview Application');
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('interview', $this->user->modules));
            return $next($request);
        });
    }

    public function index(InterviewApplicationDatatable $dataTable)
    {
        $viewInterviewPermission = user()->permission('view_interview');
        $isAssigned = AssignInterview::where('assign_to',user()->id)->count();
    abort_403(!( 
         $isAssigned || in_array('admin', user_roles()) ||
        ($isAssigned && $viewInterviewPermission=="all") 
       ));
    
        return $dataTable->render('interview-application.index', $this->data);
    }

   

    /**
     * @param StoreRequest $request
     * @return array
     */
   
     public function delete($id){
        ApplyJob::where('id',$id)->delete();
        FamilyDetail::where('candidate_id',$id)->delete();
        WorkExperience::where('candidate_id',$id)->delete();
        Reference::where('candidate_id',$id)->delete();
        AcademicQualification::where('candidate_id',$id)->delete();
        InterviewLevel::where('interview_id',$id)->delete();
        AssignInterview::where('interview_id',$id)->delete();
    }

    public function show($id)
    {
        $viewInterviewPermission = user()->permission('view_interview');

  if (!($viewInterviewPermission == 'all' || $viewInterviewPermission == 'owned' || in_array('admin', user_roles()))) {
    abort(403);
}

             
            
        $this->interview = ApplyJob::with('familyDetails','qualification','work','reference')->findOrFail($id);
        $this->interviewLevel = InterviewLevel::where('interview_id',$id)->get();
        if (request()->ajax())
        {
            $html = view('interview-application.ajax.show', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'interview-application.ajax.show';
        return view('interview-application.create', $this->data);
    }

    public function destroy(InterviewApplicationDatatable $dataTable,$id)
    {
        $viewInterviewPermission = user()->permission('delete_interview');
        abort_403(!( $viewInterviewPermission  == 'all'  || ($viewInterviewPermission  == 'owned') || (in_array('admin', user_roles()))));
    
        ApplyJob::where('id',$id)->delete();
        return $dataTable->render('interview-application.index', $this->data);
    }

    public function assignInterview(Request $request,$id){
        $assignInterviewnew =  new AssignInterview();
        $user = User::find($request->assign_id);
        $assignInterviewnew->assign_from = user()->id;
        $assignInterviewnew->assign_to=$request->assign_id;
        $assignInterviewnew->interview_id=$id;
        $assignInterviewnew->save();
        $redirectUrl=route('interview.index');
        $assignedName = $user->name;
        $successMessage = __('Assign To') . ' - ' . $assignedName;

// Return the success response with the concatenated message
return Reply::successWithData($successMessage, ['redirectUrl' => $redirectUrl]);

    }
    public function remarks($id)
    { 
        $this->name=user()->name;
        $this->userid=$id;
        $this->level1 = InterviewLevel::with('user')->where('interview_id', (int)$id)
                                 ->where('level',"level1")
                                 ->first();
        $this->level2 = InterviewLevel::with('user')->where('interview_id', (int)$id)
                                 ->where('level',"level2")
                                 ->first();
                              
    
        if (request()->ajax())
        {
            $html = view('interview-application.ajax.remarks',$this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }
        
        $this->view = 'interview-application.ajax.remarks';
        return view('interview-application.create', $this->data);
    }

    public function store(Request $request){
        if($request->level=='level1'){
        $request->validate([
            'place' => 'required|string|max:255',
            'description' => 'required|string',
            'remarks' => 'required|in:Selected,Recommended,On Hold,Rejected',
        ]);
    }

    if($request->level=='level2'){
        $request->validate([
            'place2' => 'required|string|max:255',
            'description2' => 'required|string',
            'remarks2' => 'required|in:Selected,Recommended,On Hold,Rejected',
        ],[
            'place2.required' => 'The Place field is required.',
            'place2.max' => 'The Place field must not exceed 200 characters.',
            'description2.required' => 'The Description field is required.',
            'remarks2.required' => 'The Remarks field is required.',
        ]);
    }
    try {
        if($request->level=='level1'){
            
            InterviewLevel::updateOrCreate(
            ['level'=>'level1',
            'interview_id' => $request->userId,
            'user_id' => user()->id,
            ],
            [
                'interview_id' => $request->userId,
                'user_id' => user()->id,
                'place' => $request->input('place'),
                'description' => $request->input('description'),
                'status' => $request->input('remarks'),
                'level'=>$request->input('level')
            ]);
        }

      
        if($request->level=='level2'){
            
            InterviewLevel::updateOrCreate(
                ['level'=>'level2',
                'interview_id' => $request->userId,
                'user_id' => user()->id,
            ],
                [
                'interview_id' => $request->userId,
                'user_id' => user()->id,
                'place' => $request->input('place2'),
                'description' => $request->input('description2'),
                'status'=>$request->input('remarks2'),
                'level'=>$request->input('level'),
            ]);
        }
       
        $redirectUrl=route('interview.index');
        return Reply::successWithData(__('Record saved successfull'), ['redirectUrl' => $redirectUrl]);

    } catch (\Throwable $th) {
        return Reply::error(__('Something went wrong'));

    }
     

    }
    public function remarkUpdate(Request $request){
   
    }
  
 public function updateForm(Request $request,$id){
    $updateInterviewPermission = user()->permission('update_interview');
    abort_403(!($updateInterviewPermission  == 'all' || ($updateInterviewPermission  == 'owned') || (in_array('admin', user_roles()))));
 }
  public function statusUpdate(Request $request,$id){
    $updateInterviewStatusPermission = user()->permission('update_interview_status');
    abort_403(!($updateInterviewStatusPermission  == 'all'|| (in_array('admin', user_roles()))));
    InterviewLevel::where('interview_id', $id)
      ->update([
            'status'=>$request->input('status'),
      ]);
    $redirectUrl=route('interview.index');
    return Reply::successWithData(__('Status Update Successfully'), ['redirectUrl' => $redirectUrl]);
}

}
