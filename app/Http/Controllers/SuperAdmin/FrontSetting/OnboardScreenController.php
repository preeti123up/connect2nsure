<?php

namespace App\Http\Controllers\SuperAdmin\FrontSetting;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Models\OnboardScreens;
use Illuminate\Http\Request;
use App\Helper\Files;

class OnboardScreenController extends AccountBaseController
{

  function index()
  {
    $this->pageTitle = 'superadmin.menu.detailss';
    $this->activeSettingMenu = 'details';
    $this->type = 'tab';
    $this->details = OnboardScreens::get();
    $this->view = 'super-admin.front-setting.onboard-screen.read-more-data';
    return view('super-admin.front-setting.onboard-screen.index', $this->data);
  }

  function create(Request $request)
  {
    $this->type='tab';
    return view('super-admin.front-setting.onboard-screen.create', $this->data);
  }

  function store(Request $request)
  {
    $details = new OnboardScreens();
    $details->title = $request->title ?? null;
    $details->description = $request->description ?? null;
    if ($request->hasFile('image')) {
      $details->image = Files::uploadLocalOrS3($request->image, 'front/onboard-screen');
    }
    $details->save();
    return Reply::successWithData(__('messages.recordSaved'), []);
  }

  function edit(Request $request, $id)
  {
    $this->details = OnboardScreens::where('id', $id)->first();
    $this->type = 'tab';
    return view('super-admin.front-setting.onboard-screen.edit', $this->data);
  }

  function update(Request $request, $id)
  {
    $details = OnboardScreens::findOrFail($id);
    $details->title = $request->title ?? null;
    $details->description = $request->description ?? null;
    if ($request->hasFile('image')) {
      $details->image = Files::uploadLocalOrS3($request->image, 'front/onboard-screen');
    }
    $details->save();
    return Reply::successWithData(__('messages.recordSaved'), []);
  }

  public function deleteDataReadMore(Request $request, $id)
  {
    OnboardScreens::destroy($id);
    return Reply::successWithData(__('messages.deleteSuccess'), []);
  }
}
