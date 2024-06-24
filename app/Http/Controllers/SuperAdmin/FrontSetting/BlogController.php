<?php

namespace App\Http\Controllers\SuperAdmin\FrontSetting;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Models\SuperAdmin\Blog;
use Illuminate\Http\Request;
use App\Helper\Files;

class BlogController extends AccountBaseController
{

  function index()
  {
    $this->pageTitle = 'superadmin.menu.detailss';
    $this->activeSettingMenu = 'details';
    $this->type = 'tab';
    $this->details = Blog::get();
    $this->view = 'super-admin.front-setting.blog.read-more-data';
    return view('super-admin.front-setting.blog.index', $this->data);
  }

  function create(Request $request)
  {
    $this->type='tab';
    return view('super-admin.front-setting.blog.create', $this->data);
  }

  function store(Request $request)
  {
    $details = new Blog();
    $details->title = $request->title ?? null;
    $details->description = $request->description ?? null;
    $details->video = $request->video ?? null;
    if ($request->hasFile('image')) {
      $details->image = Files::uploadLocalOrS3($request->image, 'front/blog');
    }
    $details->save();
    return Reply::successWithData(__('messages.recordSaved'), []);
  }

  function edit(Request $request, $id)
  {
    $this->details = Blog::where('id', $id)->first();
    $this->type = 'tab';
    return view('super-admin.front-setting.blog.edit', $this->data);
  }

  function update(Request $request, $id)
  {
    $details = Blog::findOrFail($id);
    $details->title = $request->title ?? null;
    $details->description = $request->description ?? null;
    $details->video = $request->video ?? null;
    if ($request->hasFile('image')) {
      $details->image = Files::uploadLocalOrS3($request->image, 'front/blog');
    }
    $details->save();
    return Reply::successWithData(__('messages.recordSaved'), []);
  }

  public function deleteDataReadMore(Request $request, $id)
  {
    Blog::destroy($id);
    return Reply::successWithData(__('messages.deleteSuccess'), []);
  }
}
