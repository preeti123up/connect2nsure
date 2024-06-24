<?php

namespace App\DataTables;

use App\DataTables\BaseDataTable;
use Yajra\DataTables\Html\Button;
use App\Models\Asset;
use App\Models\LendAsset;
use App\Models\User;


class AssetDataTable extends BaseDataTable
{

    private $editAssetsPermission;
    private $deleteAssetsPermission;
    private $viewAssetsPermission;
    private $lendAssetsPermission;
    private $viewAssetsHistoryPermission;


    public function __construct()
    {
        parent::__construct();
        $this->editAssetsPermission = user()->permission('edit_assets');
        $this->deleteAssetsPermission = user()->permission('delete_assets');
        $this->viewAssetsPermission = user()->permission('view_assets');
        $this->lendAssetsPermission = user()->permission('lend_assets');
        $this->viewAssetsHistoryPermission = user()->permission('view_assets_history');


    }

    public function dataTable($query)
    {
        $datatables = datatables()->collection($query);
        $datatables->addIndexColumn();
        $datatables->addColumn('id', function ($row) {
            return $row->id;
        });
        $datatables->addColumn('asset_picture', function ($row) {
            if ($row->asset_picture) {
                return '<a href="' . asset('/user-uploads/asset/' . $row->asset_picture) . '" data-lightbox="asset-image">' .
                    '<img src="' . asset('/user-uploads/asset/' . $row->asset_picture) . '" alt="Asset Picture" style="max-width: 100px; max-height:100px;"/>' .
                    '</a>';
            } else {
                return '--';
            }
        });
        $datatables->addColumn('asset_name', function ($row) {
            return $row->asset_name;
        });
        $datatables->addColumn('lent_to', function ($row) {
            if ($row->lendedAsset->count()) {
                $user = User::find($row->lendedAsset->first()->user_id);
                if ($user) {
                    return view('components.employee', ['user' => $user]);
                }
            }
            return '--';
        });
        $datatables->addColumn('status', function ($row) {
            if (in_array('admin', user_roles()) || $this->viewAssetsPermission == "all") {
                $statusClass = ($row->status == 'Returned' || $row->status == 'Lost' || $row->status == 'Lended' || $row->status == 'Non Functional' || $row->status == 'Damaged' || $row->status === 'Under Maintenance' || $row->status === 'Rejected') ? 'text-red' : 'text-light-green';
                return '<i class="fa fa-circle mr-1 ' . $statusClass . ' f-10"></i>' . $row->status;
            }
            if ($row->lendedAsset->first()->admin_status === "Lended" || $row->lendedAsset->first()->admin_status === "Approved") {
                return '<i class="fa fa-circle mr-1 text-light-green f-10"></i>' . $row->lendedAsset->first()->lend_status . ' (' . $row->lendedAsset->first()->admin_status . ')';
            } else {
                return '<i class="fa fa-circle mr-1 text-red f-10"></i>' . $row->lendedAsset->first()->lend_status . ' (' . $row->lendedAsset->first()->admin_status . ')';
            }
        });
        $datatables->addColumn('date', function ($row) {
            $data = '<div>';
            if ($row->lendedAsset->count()) {
                $data .= '<p class="mb-0"><strong>Allocation Date:</strong>' . ($row->lendedAsset->first()->given_date ? \Carbon\Carbon::createFromTimestamp(strtotime($row->lendedAsset->first()->given_date))->format('d M Y') : '--') . '</p>';
                $data .= '<p class="mb-0"><strong>Estimated Return:</strong>' . (\Carbon\Carbon::createFromTimestamp(strtotime($row->lendedAsset->first()->estimated_return_date))->format('d M Y') ?? '') . '</p>';
                $data .= '<p><strong>Return Date: </strong>' . ($row->lendedAsset->first()->date_of_return !== NULL ? \Carbon\Carbon::createFromTimestamp(strtotime($row->lendedAsset->first()->date_of_return))->format('d M Y') : '--') . '</p>';
            } else {
                $data .= '<p>--</p>';
            }
            $data .= '</div>';
            return $data;
        });
        $datatables->addColumn('action', function ($row) {
            $action = '';

            // Check if the user is an admin or has permission to view asset history
            if (in_array('admin', user_roles()) || $this->viewAssetsHistoryPermission == "all") {
                $action .= '<a href="' . route('asset.list', [$row->id]) . '" class="dropdown-item openRightModal"><i class="fa fa-eye mr-2"></i>' . __('app.view') . '</a>';
            }

            // Check if the user is an admin or has permission to edit assets
            if (in_array('admin', user_roles()) || $this->editAssetsPermission == 'all') {
                $action .= '<a class="dropdown-item openRightModal" href="' . route('asset.edit', [$row->id]) . '" data-asset-id="' . $row->id . '">
                <i class="fa fa-edit mr-2"></i>
                ' . trans('app.edit') . '
            </a>';
            }

            // Check if the user is an admin or has permission to delete assets
            if (in_array('admin', user_roles()) || $this->deleteAssetsPermission == 'all') {
                $action .= '<a class="dropdown-item delete-table-row" href="javascript:;" data-asset-id="' . $row->id . '">
            <i class="fa fa-trash mr-2"></i>
            ' . trans('app.delete') . '
           </a>';
            }

            // Check if the user is an admin or has permission to lend assets, and the asset status is 'Available'
            if ((in_array('admin', user_roles()) || $this->lendAssetsPermission == 'all') && $row->status == 'Available') {
                $action .= '<a class="dropdown-item openRightModal"  href="' . route('lend.asset', [$row->id]) . '">
                <i class="fa fa-solid fa-share"></i>
                ' . 'Lend' . '
                </a>';
            }

            // Check if the asset status is 'Lended' and the admin_status of the first lended asset is 'Lended'
            if ($row->status == 'Lended' && $row->lendedAsset->first() && $row->lendedAsset->first()->admin_status == "Lended") {
                $action .= '<a class="dropdown-item openRightModal"  href="' . route('return.lended.asset', [$row->lendedAsset->first()->id]) . '">
        <i class="fa fa-solid fa-share"></i>
        ' . 'Return' . '
        </a>';
            }

            // If there are any action items, render the dropdown menu
            if ($action) {
                $action = '<div class="task_view">
            <div class="dropdown">
                <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" type="link"
                    id="dropdownMenuLink-' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="icon-options-vertical icons"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-' . $row->id . '" tabindex="0">
                    ' . $action . '
                </div>
            </div>
        </div>';
            }

            return $action;
        });

        $datatables->rawColumns(['status', 'action', 'asset_picture', 'date']);
        return $datatables;
    }

    public function html()
    {
        $dataTable = $this->setBuilder('assets-table', 2)
            ->parameters([
                'initComplete' => 'function () {
                    window.LaravelDataTables["assets-table"].buttons().container()
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

    public function query()
    {
        $request = $this->request();
        if ($this->viewAssetsPermission == "all" || in_array('admin', user_roles())) {
            $assetsQuery = Asset::with([
                'lendedAsset' => function ($query) {
                    $query->where('admin_status', '!=', 'Approved')
                        ->latest();
                }
            ])->where('company_id', api_user()->company_id);
        } else {
            $assetsQuery = Asset::with([
                'lendedAsset' => function ($query) {
                    $query->where('user_id', '=', user()->id)->latest();
                }
            ])->where('company_id', api_user()->company_id);
        }


        if ($request->employee != 'all' && $request->employee != '') {
            $assetsQuery->whereHas('lendedAsset', function ($query) use ($request) {
                $query->where('user_id', $request->employee);
            });
        }


        if ($request->status != 'all' && $request->status != '') {
            $assetsQuery->where('status', $request->status);
        }

        if ($request->type != 'all' && $request->type != '') {
            $assetsQuery->where('asset_type', $request->type);
        }

        $assets = $assetsQuery->get();


        return $assets;
    }

    protected function getColumns()
    {
        $columns = [
            ['data' => 'id', 'name' => 'id', 'title' => 'Id'],
            ['data' => 'asset_picture', 'name' => 'asset_picture', 'title' => 'Asset Picture'],
            ['data' => 'asset_name', 'name' => 'asset_name', 'title' => 'Asset Name'],
            ['data' => 'lent_to', 'name' => 'lent_to', 'title' => 'Lent To'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
            ['data' => 'date', 'name' => 'date', 'title' => 'Date']
        ];


        $columns[] = ['data' => 'action', 'name' => 'action', 'title' => 'Action'];


        return $columns;
    }

}
