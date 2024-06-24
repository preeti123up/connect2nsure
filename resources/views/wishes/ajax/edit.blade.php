<div class="row">
    <div class="col-sm-12">
      
        <x-form id="update-wishes-data-form" enctype="multipart/form-data" >
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('modules.wishes.editWishes')</h4>
                <div class="row pl-20 pr-20 pt-20">
                    <div class="col-lg-4 col-md-6">
                        <x-forms.label class="mt-3" fieldId="" :fieldLabel="__('modules.wishes.wishesType')" fieldRequired="true">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select  class="form-control multiple-users select-picker" name="wishes_type" 
                                data-live-search="true">
                                <option value="">--</option>
                                @if (isset($celebrationType))
                                    @foreach ($celebrationType as $type)
                                        <option value="{{ $type->id}}" @if ($wishes->type==$type->id) selected @endif>{{ $type->celebration_value}}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </x-forms.input-group>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group my-3">
                            <x-forms.label fieldId="colorselector" fieldRequired="true"
                                           :fieldLabel="__('modules.wishes.wishesFontColor')">
                            </x-forms.label>
                            <x-forms.input-group class="color-picker">
                                <input type="text" class="form-control height-35 f-14"
                                       value="{{$wishes->font_color }}"
                                       placeholder="{{ __('placeholders.colorPicker') }}" name="global_header_color">
                                <x-slot name="append">
                                    <span class="input-group-text height-35 colorpicker-input-addon"><i></i></span>
                                </x-slot>
                            </x-forms.input-group>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group my-3">
                            <label class="f-14 text-dark-grey mb-12 w-100" for="usr">@lang('modules.wishes.wisheRTL')</label>
                            <div class="d-flex">
                                <x-forms.radio fieldId="rtl-yes" :fieldLabel="__('Right')" fieldName="rtl" fieldValue="1"
                                               :checked="($wishes->rtl == 1) ? 'checked' : ''">
                                </x-forms.radio>
                                <x-forms.radio fieldId="rtl-no" :fieldLabel="__('Left')" fieldValue="0" fieldName="rtl"
                                               :checked="($wishes->rtl == 0) ? 'checked' : ''">
                                </x-forms.radio>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 ntfcn-tab-content-left w-100  ">
                        <div class="row">
                        <div class="col-sm-12">
                        <x-forms.file
                            class="mr-0 mr-lg-2 mr-md-2"
                            :fieldLabel="__('modules.wishes.wishesBackgroundImage')"
                            :fieldRequired="true"
                            :fieldValue="$wishes->background_image"
                             fieldName="background_image"
                             fieldId="file-upload-dropzone"
                             onchange="previewImage()"

                        />
                        </div>
                        </div>
                         <img id="currentImage" src="{{ asset('user-uploads/background_images/' . $wishes->background_image) }}" height="70" width="70" alt="Current Image" style="max-width: 300px;">

                    </div>
                    
                      <div class="col-lg-6 col-md-12 ntfcn-tab-content-left w-100  ">
                        <div class="row">
                            <div class="col-sm-12">
                                <x-forms.file
                                    class="mr-0 mr-lg-2 mr-md-2"
                                    :fieldLabel=" __('Image for App') "  :fieldRequired="true" fieldName="app_image"
                                    fieldId="file-upload-dropzone_web"
                                    onchange="previewImageApp()"

                                    />
                            </div>
                            <span class="text-red" id="file"></span>

                        </div>
                         <img id="currentImageApp"  src="{{ asset('user-uploads/app_image/' . $wishes->app_image) }}" height="70" width="100" alt="Current Image" style="max-width: 300px;">

                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="form-group">
                            <x-forms.label class="my-3" fieldId="message" :fieldRequired="true"
                                :fieldLabel="__('modules.wishes.wishesMessage')">
                            </x-forms.label>
                            <textarea name="message" rows="4" id="message" class="form-control">{{$wishes->message}}</textarea>
                        </div>
                    </div>
                </div>

                <x-form-actions>
                    <x-forms.button-primary id="update-wishes-form" class="mr-3" icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-cancel :link="route('wishes.index')" class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-form-actions>

            </div>
        </x-form>
    </div>
</div>
<script src="{{ asset('vendor/jquery/bootstrap-colorpicker.js') }}"></script>
<script src="{{ asset('vendor/jquery/image-picker.min.js') }}"></script>

<script>
    function previewImage() {
      const input = document.getElementById('file-upload-dropzone');
      const currentImage = document.getElementById('currentImage');

      const file = input.files[0];

      if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
          currentImage.src = e.target.result;
        };

        reader.readAsDataURL(file);
      }
    }
  </script>

<script>
    function previewImageApp() {
      const input = document.getElementById('file-upload-dropzone_web');
      const currentImage = document.getElementById('currentImageApp');

      const file = input.files[0];

      if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
          currentImage.src = e.target.result;
        };

        reader.readAsDataURL(file);
      }
    }
  </script>

<script>
    $(document).ready(function(){
        $('.select-picker').selectpicker('refresh');
        $('.color-picker').colorpicker();
        $('.image-picker').imagepicker();

      $('#update-wishes-form').click(function() {
            var formData=new FormData($('#update-wishes-data-form')[0]);
            $.ajax({
                url: "{{route('wishes.update', base64_encode($wishes->id))}}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                   
                    window.location.href='{{route('wishes.index')}}';
                },
                error:function(e){
                    console.log(e);
                }
            });
        });
    })
</script>




