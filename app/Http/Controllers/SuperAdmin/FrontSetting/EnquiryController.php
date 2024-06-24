<?php

namespace App\Http\Controllers\SuperAdmin\FrontSetting;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Models\SuperAdmin\Enquiry;
use Illuminate\Http\Request;
use App\Helper\Files;

class EnquiryController extends AccountBaseController
{

  function index()
  {
    $this->pageTitle = 'superadmin.menu.detailss';
    $this->activeSettingMenu = 'details';
    $this->type = 'tab';
    $this->details = Enquiry::get();
    $this->view = 'super-admin.front-setting.enquiry.read-more-details';
    return view('super-admin.front-setting.enquiry.index', $this->data);
  }

 
}
