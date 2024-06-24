<?php

namespace App\DataTables;

use App\Models\Team;
use App\DataTables\BaseDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\Attendance;

class PendingAttendanceDataTable extends BaseDataTable
{

    private $editDepartmentPermission;
    private $deleteDepartmentPermission;
    public $arr = [];


    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn(
                'name',
                function ($row) {
                    return '<h5 class="mb-0 f-13 text-darkest-grey"><a href="' . route('employees.show', [$row->user->id]) . '" class="openRightModal">' . $row->user->name . '</a></h5>';
                }
            )
            ->addColumn(
                'In',
                function ($row) {
                    if($row->clock_in_outside_reason)
                    'In'.':'.$row->clock_in_outside_reason;
                    else
                    'N/A';
                    if($row->clock_in_latitude && $row->clock_in_longitude)
                    return $row->clock_in_time->timezone(company()->timezone)->translatedFormat(company()->time_format) . ' '.
                    '<a href="https://www.google.com/maps/search/?api=1&query=' . $row->clock_in_latitude . '%2C' . $row->clock_in_longitude . '" target="_blank">Open in Maps</a>';

                }
            )
            ->addColumn(
                'Out',
                function ($row) {
                    if($row->clock_out_outside_reason)
                    'In'.':'.$row->clock_out_outside_reason;
                    else
                    'N/A';
                    if($row->clock_out_latitude && $row->clock_out_latitude)
                    return $row->clock_out_time->timezone(company()->timezone)->translatedFormat(company()->time_format) . ' '.
                    '<a href="https://www.google.com/maps/search/?api=1&query=' . $row->clock_out_latitude . '+%2C' . $row->clock_out_longitude . '" target="_blank">Open in Maps</a>';
                 
                }
            )
            ->addColumn(
                'Date',
                function ($row) {
                   
                  return $row->created_at->translatedFormat(company()->date_format);                 
                }
            )
            ->addColumn('action', function ($row) {

                $action = '<div class="task_view">

                    <div class="dropdown">
                        <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" type="link"
                            id="dropdownMenuLink-' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-options-vertical icons"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-' . $row->id . '" tabindex="0">';
                        $action .='<a class="dropdown-item"  href="'.route('pendingAttendance', [$row->id]) .'"/><i class="fa fa-eye mr-2"></i></i>
                        '.'view'.'</a>';
            if(!($row->status=="approved")){
                    $action .= '<a class="dropdown-item attendance-action-approved" data-attendance-id='. $row->id.'
                    data-attendance-action="approved" href="javascript:;" >
                    <i class="fa fa-check mr-2"></i>
                    '.
                     "Approved" .'
                </a>';

                    $action .= '<a data-attendance-id='.$row->id.' data-attendance-action="rejected"
                    class="dropdown-item  attendance-action-rejected" href="javascript:;">
                    <i class="fa fa-times mr-2"></i>'.
                     "Reject" .'
                </a>';
                }

                $action .= '</div>
                    </div>
                </div>';

                return $action;
            })

          
            ->addIndexColumn()
            ->smart(false)
            ->setRowId(function ($row) {
                return 'row-' . $row->id;
            })
            ->rawColumns([ 'name','In','Out','Date','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Team $model
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function query(Attendance $model)
    {
        $request = $this->request();
        return $model->with('user')->where('status', 'pending');

    }

    public function child($child)
    {
        foreach ($child as $item) {
            array_push($this->arr, $item->id);

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
        $dataTable = $this->setBuilder('pendingAttendance-table', 2)
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["pendingAttendance-table"].buttons().container()
                    .appendTo("#table-actions")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    })
                }',
            ]);
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
           
            __('app.name') => ['data' => 'name', 'name' => 'name', 'title' => __('app.name')],
            __('In') => ['data' => 'In', 'name' => 'In', 'title' => __('In')],
            __('Out') => ['data' => 'Out', 'name' => 'Out', 'title' => __('Out')],
            __('Date') => ['data' => 'Date', 'name' => 'Date', 'title' => __('Date')],
            Column::computed('action', __('app.action'))
            ->exportable(false)
            ->printable(false)
            ->orderable(false)
            ->searchable(false)
            ->addClass('text-right pr-20')
        ];
    }

}
