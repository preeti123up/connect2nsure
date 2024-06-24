<?php

namespace App\Http\Controllers;

use App\Models\CelebrationType;
use App\Models\Wishes;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use DateTimeZone;
use App\Helper\Reply;
use App\Http\Requests\Wishes\CreateRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Helper\Files;

class WishesController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('app.menu.wishes_setting');
        $this->activeSettingMenu = 'wishes';
    }


    public function index()
    {
        if (\request()->ajax()) {
            $wishes = Wishes::with('celebration_type')->where(['company_id' => company()->id])->get();
            $i=1;
            $data = DataTables::of($wishes)
            ->addColumn(
                'id',
                function ($row) use (&$i){
                    return $i++;
                }
            )
                ->addColumn(
                    'WishesType',
                    function ($row) {
                        return $row->celebration_type->celebration_value;
                    }
                )
                ->addColumn(
                    'Image',
                    function ($row) {
                        // Assuming 'image' is the column name in your database
                        $imageUrl = asset('user-uploads/background_images/' . $row->background_image);
                 
                        return '<img src="' . $imageUrl . '" alt="Image" class="img-thumbnail" width="50">';
                    }
                )
                ->addColumn(
                    'AppImage',
                    function ($row) {
                        // Assuming 'image' is the column name in your database
                        $imageUrl = asset('user-uploads/app_image/' . $row->app_image);
                 
                        return '<img src="' . $imageUrl . '" alt="Image" class="img-thumbnail" width="50">';
                    }
                )
                ->addColumn(
                    'action',
                    function ($row) {
                        return '<div class="task_view">  
                                    <a href="' . route('wishes.edit', ['id' => base64_encode($row->id)]) . '" class="task_view_more d-flex align-items-center justify-content-center edit-custom-field mr-3 openRightModal float-left mb-1 mb-lg-0 mb-md-0e">
                                        <i class="fa fa-edit icons mr-2"></i>'. __("app.edit").'
                                    </a>
                                </div>
                                <div class="task_view"> 
                                    <a data-user-id="' . $row->id . '" class="task_view_more d-flex align-items-center justify-content-center sa-params" href="javascript:;" data-id="{{ $permission->id }}">
                                        <i class="fa fa-trash icons mr-2"></i> ' . __("app.delete") . ' 
                                    </a> 
                                </div>';
                    }
                )
                ->rawColumns(['action', 'WishesType','Image','AppImage','id'])
                ->make(true);
    
            return $data;
        }
    
        return view('wishes.index', $this->data);
    }

    public function create()
    {
        abort_403(!in_array('admin', user_roles()));

        $this->redirectUrl = route('wishes.index');
        $this->celebrationType = CelebrationType::all();

        if (request()->ajax()) {
            $this->pageTitle = __('app.menu.wishes_setting');
            $html = view('wishes.ajax.create', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'wishes.ajax.create';
        return view('wishes.create', $this->data);
    }
    public function store(Request $request)
    {
        if ($request->wishes_type){
            foreach ($request->wishes_type as $index => $value) {
                $data['company_id'] = company()->id;
                $data['type'] = $value;
                $data['font_color'] = $request->input('global_header_color');
                $data['rtl'] = $request->input('rtl');
                $data['message'] = $request->input('message');
                $data['added_by'] = user()->id;
                
                if (request()->hasFile('background_image')) {
                    $check=Files::deleteFile($request->background_image, 'background_images');
                    $data['background_image'] = Files::uploadLocalOrS3(request()->background_image, 'background_images');
             
                }
                 if (request()->hasFile('app_image')) {
                    $data['app_image'] = Files::uploadLocalOrS3(request()->app_image, 'app_image');
                }
              
                $res=Wishes::create($data);
            }
            $redirectUrl = route('wishes.index');
            return Reply::success($redirectUrl ,'Wishes Added Successfully');
        } else {
            return Reply::error('Something went wrong!');
        }
    }

    public function edit($id)
    {
        abort_403(!in_array('admin', user_roles()));
        $id = base64_decode($id);
        $this->redirectUrl = route('wishes.index');
        $this->celebrationType = CelebrationType::all();
        $this->wishes = Wishes::where(['id' => $id, 'company_id' => company()->id])->first();
        if (request()->ajax()) {
            $this->pageTitle = __('app.menu.wishes_setting');
            $html = view('wishes.ajax.edit', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }
        $this->view = 'wishes.ajax.edit';
        return view('wishes.edit', $this->data);
    }


    public function update(Request $request, $id)
    {
     
        $id = base64_decode($id);
        $wishes = Wishes::find($id);
        if ($wishes != null) {
            $wishes->type = $request->input('wishes_type');
            $wishes->font_color = $request->input('global_header_color');
            $wishes->message = $request->input('message');
            $wishes->rtl = $request->input('rtl');
            if (request()->hasFile('background_image')) {
                $check=Files::deleteFile($wishes->background_image, 'background_images');
                $wishes->background_image = Files::uploadLocalOrS3(request()->background_image, 'background_images');
         
            }
              if (request()->hasFile('app_image')) {
                Files::deleteFile($wishes->app_image, 'app_image');
                $wishes->app_image = Files::uploadLocalOrS3(request()->app_image, 'app_image');
            }
           $res=$wishes->save();
            $redirectUrl = route('wishes.index');
            return Reply::success($redirectUrl ,'Wishes updated Successfully');
        } else {
            return Reply::error(' Wishes Not Found');
        }
    }

    public function destroy($id)
    {
        
        $wishes= Wishes::find($id);
        if ($wishes != null) {
            $wishes->delete();
            $redirectUrl = route('wishes.index');
            return Reply::success($redirectUrl ,'Deleted Successfully');
        } else {
            return Reply::error(' wishes Not Found');
        }
    }
}
