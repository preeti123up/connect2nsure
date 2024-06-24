<?php

namespace App\Http\Controllers\SuperAdmin\FrontSetting;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Http\Controllers\Controller;
use App\Models\SuperAdmin\Feature;
use App\Models\SuperAdmin\FeatureImage;
use App\Models\SuperAdmin\FeatureReadDetail;
use Illuminate\Http\Request;
use App\Helper\Files;


class FrontReadMoreFeatureDetailController extends AccountBaseController
{
    
          function  index(){

            $this->pageTitle = 'superadmin.menu.detailss';
            $this->activeSettingMenu = 'details';
            $this->type='tab';
           $this->details = FeatureReadDetail::get();
           $this->view = 'super-admin.front-setting.FrontFeatureReadMoreDetails.read-more-data';

            return view('super-admin.front-setting.FrontFeatureReadMoreDetails.index', $this->data);

          }

        function create(Request $request){
          // $this->detailsId = $request->detailsSettingId;
          // $this->type = $request->type
          #
          $this->feature=Feature::whereNull('front_feature_id')->where(['type' => 'image'])->select('title','id')->get();
          $this->type='tab';
          return view('super-admin.front-setting.FrontFeatureReadMoreDetails.create', $this->data);



        }

        function store(Request $request){
          $details=new FeatureReadDetail();
          $details->title = $request->title??null;
          $details->description = $request->description??null;
          $details->video = $request->video??null;
          $details->feature_id = $request->feature_id??null;
          if ($request->hasFile('image')) {
            $details->image = Files::uploadLocalOrS3($request->image, 'front/read-more');
        }
          $details->save();
          return Reply::successWithData(__('messages.recordSaved'),[]);

        
        }

        function edit(Request $request,$id){
          // $this->detailsId = $request->detailsSettingId;
          // $this->type = $request->typess
          #
          $this->feature=Feature::whereNull('front_feature_id')->where(['type' => 'image'])->select('title','id')->get();

          $this->details=FeatureReadDetail::where('id',$id)->first();
          $this->type='tab';
          return view('super-admin.front-setting.FrontFeatureReadMoreDetails.edit', $this->data);



        }

        function update(Request $request,$id){
          $details=FeatureReadDetail::findOrFail($id);
          $details->title = $request->title??null;
          $details->description = $request->description??null;
          $details->video = $request->video??null;
          $details->feature_id = $request->feature_id??null;

          if ($request->hasFile('image')) {
            $details->image = Files::uploadLocalOrS3($request->image, 'front/read-more');
        }
          $details->save();
          return Reply::successWithData(__('messages.recordSaved'),[]);
        
        }

        public function deleteDataReadMore(Request $request, $id)
        {
         FeatureReadDetail::destroy($id);

          return Reply::successWithData(__('messages.deleteSuccess'), []);

        }



}
