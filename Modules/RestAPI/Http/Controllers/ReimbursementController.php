<?php

namespace Modules\RestAPI\Http\Controllers;

use App\Helper\Reply;
use App\Models\Reimbursement;
use App\Models\EmployeeDetails;
use Carbon\Carbon;
use Froiden\RestAPI\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helper\Files;
use App\Models\LeaveFile;

class ReimbursementController extends ApiBaseController
{

    public function Reimbursment(Request $request)
    {

        $uniqueKey = uniqid();
        $inputBag = request()->all();
        unset($inputBag['body']);

        foreach ($inputBag['jsonData'] as $i => $v) {
            $Reimbursement = new Reimbursement();
            $leavefile = new LeaveFile();
            $Reimbursement->expense_type = $inputBag['jsonData'][$i]['expense_type'];
            $Reimbursement->payment_type = $inputBag['jsonData'][$i]['payment_type'];
            $Reimbursement->date_of_expense = $inputBag['jsonData'][$i]['date_of_expense'];
            $Reimbursement->purpose = $inputBag['jsonData'][$i]['purpose'];
            $Reimbursement->amount = $inputBag['jsonData'][$i]['amount'];
            $file_name = $request->file[$i] !== null ? Files::uploadLocalOrS3($request->file[$i], LeaveFile::FILE_PATH) : null;
            $Reimbursement->file = $file_name;
            $Reimbursement->uniqueId = $uniqueKey;
            $Reimbursement->user_id = api_user()->id;
            $Reimbursement->company_id = api_user()->company_id;
            $Reimbursement->save();
        }


        return response()->json(['message' => 'Data Saved Successfully', 'data' => [], 'status' => 'true']);


    }
    public function getReimbursment()
    {
        try {
            $insertedData = Reimbursement::where('user_id', api_user()->id)
                ->selectRaw('status,date_of_expense,uniqueId,COUNT(uniqueId) as uniqueIdCount, SUM(amount) as totalAmount') // Combined raw selects and all columns
                ->groupBy('uniqueId')
                ->get();
            $transformedData = $insertedData->map(function ($value) {
                $transformedItem = $value->toArray(); // Create a new array from the original item
                $groupData = Reimbursement::where('uniqueId', $value->uniqueId)->get();

                $groupData = $groupData->map(function ($item) use ($value) {
                    $item->file = $item->file ? asset_url_local_s3(LeaveFile::FILE_PATH . '/' . $item->file, true, 'image') : null;
                    return $item;
                });

                $transformedItem['groupData'] = $groupData;
                return $transformedItem;
            });
            return response()->json(['message' => 'Data Found Successfully', 'data' => $transformedData, 'status' => 'true']);

        } catch (\Throwable $th) {

            return response()->json(['message' => 'Data not found', 'data' => [], 'status' => 'fail']);
        }
    }
   public function ReimbursmentDelete($uniqueId)
    {
        Reimbursement::where('uniqueId', $uniqueId)->delete();
        return response()->json(['message' => 'Reimbursement Deleted Successfully', 'data' => [], 'status' => true]);
    }  
    
    
}