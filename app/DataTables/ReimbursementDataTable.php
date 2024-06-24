<?php

namespace App\DataTables;

use App\DataTables\BaseDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\Reimbursement;
use Illuminate\Support\Facades\DB;

class ReimbursementDataTable extends BaseDataTable
{
    public function dataTable($query)
    {
        $i = 1;
        $datatables = datatables()->collection($query);

        $datatables->addIndexColumn();
        $datatables->addColumn('id', function ($row) use (&$i) {
            return $i++;
        });
     

        $datatables->addColumn('name', function ($row) {
            return $row->name;
        });
        $datatables->addColumn('uniqueIdCount', function ($row) {
            return $row->uniqueIdCount;
        });
      
        $datatables->addColumn('totalAmount', function ($row) {
            return $row->totalAmount;
        });
      
        
        $datatables->addColumn('status', function ($row) {
            $status = $row->status;
            $colorClass = '';
             switch ($status) {
                case 'Approved':
                    $colorClass = 'text-success'; 
                    break;
                case 'Rejected':
                    $colorClass = 'text-danger'; 
                    break;
                case 'Pending':
                    $colorClass = 'text-danger'; 
                    break;
            }
             $statusModified=$row->already_paid=="true"?"Already Paid":$status;
             $colorClass=$row->already_paid=="true"?"text-success":$colorClass;
             
            return '<span class="' . $colorClass . '">' . $statusModified . '</span>';            
            
        });
        $datatables->addColumn('action', function ($row) {
            $action = '<div class="task_view">
                <div class="dropdown">
                    <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" type="link"
                        id="dropdownMenuLink-' . $row->uniqueId . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-options-vertical icons"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-' . $row->uniqueId . '" tabindex="0">';
            $action .= '<a href="' . route('reimbursement.list', ['id' => $row->id, 'uniqueId' => $row->uniqueId]) . '" class="dropdown-item"><i class="fa fa-eye mr-2"></i>' . __('app.view') . '</a>';
          
           

            if (in_array('admin', user_roles())) {
               if($row->status !=="Approved" && $row->status !=="Rejected" && $row->already_paid == "false" ){
                    $action .= '<a class="dropdown-item reimbursement-action-approved" data-reimbursement-id=' . $row->uniqueId . '
                    data-reimbursement-action="approved" href="javascript:;">
                    <i class="fa fa-check mr-2"></i>
                    ' . __('app.approve') . '
                </a>';
                }
                if($row->status !=="Rejected" && $row->status !=="Approved" && $row->already_paid == "false"){
                    $action .= '<a class="dropdown-item reimbursement-action-rejected" data-reimbursement-id=' . $row->uniqueId . '
                    data-reimbursement-action="rejected"   href="javascript:;">
                        <i class="fa fa-times mr-2"></i>
                        ' . __('app.reject') . '
                    </a>';
                }
                  if($row->already_paid == "false" && $row->status !=="Approved" && $row->status !=="Rejected" ){
                    $action .= '<a class="dropdown-item already_paid-action-approved" data-reimbursement-id=' . $row->uniqueId . '
                    data-reimbursement-action="already paid"   href="javascript:;">
                    <i class="fa fa-check mr-2"></i>
                        ' . __('Already Paid') . '
                    </a>';
                }
            }

            $action .= '</div>
                </div>
            </div>';

            return $action;
        });
        $datatables->rawColumns(['status','action']);
        return $datatables;
    }

    public function html()
    {
        $dataTable = $this->setBuilder('reimbursements-table', 2)
            ->parameters([
                'initComplete' => 'function () {
                    window.LaravelDataTables["reimbursements-table"].buttons().container()
                        .appendTo( "#table-actions");
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $(".select-picker").selectpicker();
                }',
            ]);

        if (canDataTableExport()) {
            $dataTable->buttons(Button::make(['extend' => 'excel', 'text' => '<i class="fa fa-file-export"></i> ' . trans('app.exportExcel')]));
        }

        return $dataTable;
    }

    public function query(Reimbursement $model)
    {
        
       
         if (in_array('admin', user_roles())) {
            $reimbursements = Reimbursement::join('users', 'reimbursement.user_id', '=', 'users.id')
            ->where('reimbursement.company_id',user()->company_id)
            ->select('reimbursement.*', 'users.name as name')
            ->selectRaw('COUNT(reimbursement.uniqueId) as uniqueIdCount') // Count for each uniqueId
            ->selectRaw('SUM(reimbursement.amount) as totalAmount') // Total amount for each uniqueId
            ->groupBy('reimbursement.uniqueId')
            ->orderByRaw("CASE WHEN reimbursement.status = 'rejected' THEN 0 ELSE 1 END")
            ->get();
            } else {
                $reimbursements = Reimbursement::join('users', 'reimbursement.user_id', '=', 'users.id')
                ->where('reimbursement.company_id',user()->company_id)
                ->select('reimbursement.*', 'users.name as name')
                ->selectRaw('COUNT(reimbursement.uniqueId) as uniqueIdCount') 
                ->selectRaw('SUM(reimbursement.amount) as totalAmount') 
                ->groupBy('reimbursement.uniqueId')
                ->orderByRaw("CASE WHEN reimbursement.status = 'rejected' THEN 0 ELSE 1 END")
                ->where('reimbursement.user_id',user()->id)
                ->get();
              
            }
        
      

        return $reimbursements;
    }

    protected function getColumns()
    {
        return [
            'id' => [
                'data' => 'id',
                'orderable' => false,
                'searchable' => false,
                'visible' => true,
                'title' => 'Id'
            ],
          
            'name' => [
                'data' => 'name',
                'name' => 'name',
                'orderable' => false,
                'searchable' => false,
                'visible' => true,
                'title' => __('app.name')
            ],
          
            'Total Expense' => [
                'data' => 'uniqueIdCount',
                'orderable' => false,
                'searchable' => false,
                'visible' => true,
                'title' => "Total Expense"
            ],
            	
            'Total Amount' => [
                'data' => 'totalAmount',
                'orderable' => false,
                'searchable' => false,
                'visible' => true,
                'title' => "Amount"
            ],
         
            'Status' => [
                'data' => 'status',
                'orderable' => false,
                'searchable' => false,
                'visible' => true,
                'title' => "Status"
            ],
            'Action' => [
                'data' => 'action',
                'orderable' => false,
                'searchable' => false,
                'visible' => true,
                'title' => "Action"
            ],
        ];
    }
}
