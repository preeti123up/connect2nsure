<?php

namespace App\DataTables;

use Carbon\Carbon;
use App\Models\Holiday;
use App\Models\Designation;
use App\DataTables\BaseDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use App\Models\Asset;
use App\Models\User;
use App\Models\ApplyJob;
use App\Models\AssignInterview;
use App\Models\InterviewLevel;


class InterviewApplicationDatatable extends BaseDataTable
{

    private $deleteInterviewPermission;
    private $viewInterviewPermission;
    private $editInterviewPermission;

    public function __construct()
    {
        parent::__construct();
        $this->deleteInterviewPermission = user()->permission('delete_Interview');
        $this->viewInterviewPermission = user()->permission('view_interview');
        $this->editInterviewPermission = user()->permission('edit_interview');
    }

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $remarks = [
            'Selected',
            'Recommended',
            'On Hold',
            'Rejected',
            'Pending'
        ];
       
        $user=User::all();
        $i=1;
        return datatables()
        ->eloquent($query)
        ->addIndexColumn()
        ->addColumn(
            'id',
            function ($row) use (&$i){
                return $i++;
            }
        )
      ->addColumn('Assign', function ($row) use ($user) {
        $assign = '<select class="form-control select-picker assign_role" data-user-id="' . $row->id . '">';
        $assign.= '<option value=" " ' . 'selected' . 'disabled'.'>' . 'Select---'. '</option>';
        $assignInterview=AssignInterview::where('interview_id',$row->id)->get();
        foreach ($user as $item) {
            $selected = '';
             if ($assignInterview->count() && $assignInterview->last()->assign_to == $item->id) {
                $selected = 'selected';  
            }elseif(user()->id == $item->id){
                $selected = 'selected'; 
            }
            $assign .= '<option value="' . $item->id . '" ' . $selected . '>' . $item->name . '</option>';
        }
        $assign .= '</select>';
        return $assign;
        
        })
       ->addColumn('status',function($row) use ($remarks){
        $status = '<select class="form-control select-picker status_update" data-user-id="' . $row->id . '">';
        $status .= '<option value=" " ' . 'selected' . '>' . 'pending'. '</option>';

        $assignInterview=InterviewLevel::where(['interview_id'=>$row->id])->get();
                if($assignInterview->count()){
                        $status = $assignInterview->last()->status;
                }else{
                    $status = "Pending";
                }
        return $status;
      }) 
            ->addColumn('action', function ($row) {

                $action = '<div class="task_view">

                    <div class="dropdown">
                        <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" type="link"
                            id="dropdownMenuLink-' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-options-vertical icons"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-' . $row->id . '" tabindex="0">';

                $action .= '<a  class="dropdown-item openRightModal" href="'.route('interview.show',[$row->id]).'"><i class="fa fa-eye mr-2"></i>' . __('app.view') . '</a>';

                $assignInterview=AssignInterview::where(['interview_id'=>$row->id,'assign_to'=>user()->id])->first();
                 if(isset($assignInterview)){
                    $action .= '<a class="dropdown-item openRightModal" href="'.route('interview.remarks',[$row->id]).'" >
                    <i class="fa fa-edit mr-2"></i>
                    ' . 'Remarks' . '
                </a>';
                 }
                $action .= '</div>
                    </div>
                </div>';

                return $action;
            })
            ->smart(false)
            ->setRowId(function ($row) {
                return 'row-' . $row->id;
            })
            ->rawColumns(['id', 'action','Assign','status']);
    }

    /**
     * @param Designation $model
     * @return \Illuminate\Database\Query\Builder
     */
    public function query(ApplyJob $model)
    {
        $request = $this->request();
        $model = $model->select('*');
       $assignInterview=AssignInterview::where('assign_to',user()->id)->pluck('interview_id')->toArray();
       if(collect(user()->roles)->pluck('name')->contains('admin')){
        return $model->where('company_id',user()->company_id)->latest()->orderBy('id', 'desc');
       }
       if(isset($assignInterview)){
        $model->whereIn('id', $assignInterview)->latest()->orderBy('id', 'desc');
        return $model;
       }
  

       
    }

    public function child($child)
    {
        foreach ($child as $item) {
            $this->arr[] = $item->id;

            if ($item->childs) {
                $this->child($item->childs);
            }
        }
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $dataTable = $this->setBuilder('Designation-table')
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["Designation-table"].buttons().container()
                    .appendTo("#table-actions")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    });
                    $(".statusChange").selectpicker();
                }',
            ]);

        if (canDataTableExport()) {
            $dataTable->buttons(Button::make(['extend' => 'excel', 'text' => '<i class="fa fa-file-export"></i> ' . trans('app.exportExcel')]));
        }

        return $dataTable;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            '#' => ['data' => 'id', 'orderable' => true, 'searchable' => false, 'visible' => true, 'title' => '#'],
            __('app.name') => ['data' => 'name', 'name' => 'name', 'exportable' => true, 'title' => __('app.name')],
            __('Tecnology') => ['data' => 'technology', 'name' => 'technology', 'exportable' => true, 'title' => __('Technology')],
            __('Applied For') => ['data' => 'applied_position', 'name' => 'applied_position', 'exportable' => true, 'title' => __('Applied For')],
            __('Mobile')  => ['data' => 'mobile', 'name' => 'mobile', 'exportable' => true, 'title' => __('Mobile')],
            __('Assign')  => ['data' => 'Assign', 'name' => 'Assign', 'exportable' => true, 'title' => __('Assign')],
            __('Status')  => ['data' => 'status', 'name' => 'status', 'exportable' => true, 'title' => __('Status')],
            'action' => Column::computed('action', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->addClass('text-right pr-20')
        ];
    }

}
