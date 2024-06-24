<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use Illuminate\Http\Request;
use App\DataTables\AssetDataTable;
use App\Models\Asset;
use App\Models\User;
use App\Models\AssetDevice;
use App\Models\LendAsset;
use App\Helper\Files;
use Carbon\Carbon;


class AssetController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('Assets');
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('assets', $this->user->modules));
            return $next($request);
        });

    }

    public function index(AssetDataTable $dataTable)
    {
        $viewAssetsPermission = user()->permission('view_assets');
        abort_403(!(
            $viewAssetsPermission == 'all'
            || ($viewAssetsPermission == 'owned')
            || (in_array('admin', user_roles()))
        ));

        if ($viewAssetsPermission == 'owned') {
            $this->employees = User::where('id', user()->id)->get();
        } else {
            $this->employees = User::allEmployees(null, true, ($viewAssetsPermission == 'all' ? 'all' : null));
        }

        $this->asset = Asset::groupBy('status')->get();
        $this->assetDevice = AssetDevice::where('company_id', user()->company_id)->get();
        return $dataTable->render('asset.index', $this->data);
    }

    public function create()
    {
        $addAssetsPermission = user()->permission('add_assets');
        abort_403(!(
            $addAssetsPermission == 'all'
            || ($addAssetsPermission == 'owned')
            || (in_array('admin', user_roles()))
        ));
        $this->employees = User::allEmployees();
        $this->device = AssetDevice::where('company_id', user()->company_id)->get();
        if (request()->ajax()) {
            $html = view('asset.ajax.create', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'asset.ajax.create';

        return view('asset.create', $this->data);
    }


    public function store(Request $request)
    {
        $addAssetsPermission = user()->permission('add_assets');
        abort_403(!(
            $addAssetsPermission == 'all'
            || ($addAssetsPermission == 'owned')
            || (in_array('admin', user_roles()))
        ));

        $request->validate([
            'asset_type' => 'required',
            'asset_name' => 'required',
            'serial_number' => 'required',
            'description' => 'required',
            'image' => 'required',
            'status' => 'required',
        ]);
        $asset = new Asset();
        $asset->company_id = user()->company_id;
        $asset->asset_type = $request->input('asset_type');
        $asset->asset_name = $request->input('asset_name');
        $asset->serial_number = $request->input('serial_number');
        if ($request->hasFile('image')) {
            $asset->asset_picture = Files::uploadLocalOrS3($request->image, 'asset', 300);
        }
        $asset->location = $request->input('location');
        $asset->date = now();
        $asset->description = $request->input('description');
        $asset->status = $request->input('status');
        $asset->added_by = user()->id;
        $asset->save();
        $redirectUrl = route('asset.index');
        return Reply::successWithData(__('Record Saved Successfully'), ['redirectUrl' => $redirectUrl]);
    }


    public function edit($id)
    {
        $editAssetsPermission = user()->permission('edit_assets');
        abort_403(!(
            $editAssetsPermission == 'all'
            || ($editAssetsPermission == 'owned')
            || (in_array('admin', user_roles()))
        ));
        $this->asset = Asset::where(['id' => $id, 'company_id' => user()->company_id])->first();
        $this->device = AssetDevice::where('company_id', user()->company_id)->get();
        $this->employees = User::allEmployees();

        if (request()->ajax()) {
            $html = view('asset.ajax.edit', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'asset.ajax.edit';

        return view('asset.create', $this->data);

    }


    public function update(Request $request, $id)
    {

        $editAssetsPermission = user()->permission('edit_assets');
        abort_403(!(
            $editAssetsPermission == 'all'
            || ($editAssetsPermission == 'owned')
            || (in_array('admin', user_roles()))
        ));
        $asset = Asset::where(['id' => $id, 'company_id' => user()->company_id])->first();
        $asset->asset_type = $request->input('asset_type');
        $asset->asset_name = $request->input('asset_name');
        $asset->serial_number = $request->input('serial_number');
        if ($request->hasFile('image')) {
            $asset->asset_picture = Files::uploadLocalOrS3($request->image, 'asset', 300);
        }
        $asset->location = $request->input('location');
        $asset->date = now();
        $asset->description = $request->input('description');
        $asset->status = $request->input('status');
        $asset->save();
        $redirectUrl = route('asset.index');
        return Reply::successWithData(__('Updated Successfully'), ['redirectUrl' => $redirectUrl]);
    }



    public function destroy($id)
    {
        $deleteAssetsPermission = user()->permission('delete_assets');
        abort_403(!(
            $deleteAssetsPermission == 'all'
            || ($deleteAssetsPermission == 'owned')
            || (in_array('admin', user_roles()))
        ));
        Asset::where('id', $id)->delete();
        $redirectUrl = route('asset.index');
        return Reply::successWithData(__('messages.deleteSuccess'), ['redirectUrl' => $redirectUrl]);
    }


    public function list($id)
    {
        $this->asset = Asset::with(['assetDevice', 'lendedAsset.user'])->where('company_id', user()->company_id)->where('id', $id)->first();
        if (request()->ajax()) {
            $html = view('asset.ajax.list', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }
        $this->view = 'asset.ajax.list';
        return view('asset.create', $this->data);
    }

    public function lendAsset(Request $request)
    {
        $this->asset = Asset::where(['id' => (int) $request->id])->first();
        if (in_array('admin', user_roles())) {
            $this->employees = User::allEmployees(null, true, 'all');
        } else {
            $this->employees = User::where('id', user()->id)->get();
        }


        if (request()->ajax()) {
            $html = view('asset.ajax.lend-asset', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }
        $this->view = 'asset.ajax.lend-asset';
        return view('asset.create', $this->data);
    }



    public function storeLendAsset(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'date_given' => 'required',
            'note' => 'required',
            'date_return' => 'required',
        ]);
        $lendAsset = new LendAsset();
        $lendAsset->asset_id = $request->asset_id;
        $lendAsset->user_id = $request->user_id;
        $lendAsset->given_date = Carbon::createFromFormat('d-m-Y', $request->date_given);
        $lendAsset->estimated_return_date = Carbon::createFromFormat('d-m-Y', $request->date_return);
        $lendAsset->notes = $request->note;
        $lendAsset->admin_status = 'Lended';
        $lendAsset->lend_status = 'Lended';
        $lendAsset->added_by = user()->id;
        if ($lendAsset->save()) {
            $asset = Asset::where('id', $request->asset_id)->first();
            $asset->status = 'Lended';
            $asset->save();
        }
        $redirectUrl = route('asset.index');
        return Reply::successWithData(__('Asset Lended Successfully'), ['redirectUrl' => $redirectUrl]);
    }



    public function returnLendedAsset(Request $request)
    {
        $this->lednedAsset = LendAsset::with('user')->where('id', $request->id)->first();
        if (request()->ajax()) {
            $html = view('asset.ajax.return-asset', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }
        $this->view = 'asset.ajax.return-asset';
        return view('asset.create', $this->data);
    }


    public function storeReturnedAsset(Request $request)
    {
        $request->validate([
            'return_date' => 'required',
            'note' => 'required',
        ]);
        $lendedAsset = LendAsset::where('id', $request->id)->first();
        $lendedAsset->date_of_return = $request->return_date;
        $lendedAsset->return_notes = $request->note;
        $lendedAsset->lend_status = "Returned";
        $lendedAsset->return_by = user()->id;
        $lendedAsset->admin_status = 'Pending';
        if ($lendedAsset->save()) {
            $asset = Asset::where('id', $lendedAsset->asset_id)->first();
            $asset->status = 'Returned';
            $asset->save();
        }
        $redirectUrl = route('asset.index');
        return Reply::successWithData(__('Asset Returned Successfully'), ['redirectUrl' => $redirectUrl]);

    }

    public function changeStatus(Request $request, $id)
    {

        $lendAsset = LendAsset::where('id', (int) $id)->first();
        $lendAsset->admin_status = $request->action;
        if ($request->action == 'Approved') {
            if ($lendAsset->save()) {
                $asset = Asset::where('id', $lendAsset->asset_id)->first();
                $asset->status = 'Available';
                $asset->save();
            }
            if ($asset) {
                return Reply::success(__('Approved successfully'));
            }
        } elseif ($request->action == 'Rejected') {
            if ($lendAsset->save()) {
                $asset = Asset::where('id', $lendAsset->asset_id)->first();
                $asset->status = 'Rejected';
                $asset->save();
            }
            return Reply::success(__('Rejected successfully'));
        }


    }


}
