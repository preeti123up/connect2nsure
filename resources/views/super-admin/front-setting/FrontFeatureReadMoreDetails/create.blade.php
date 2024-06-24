<style>
    .iconpicker{
        border-radius:0.25rem !important;
    }
</style>
<div class="modal-header">
    <h5 class="modal-title">@lang('app.addNew') @lang('superadmin.feature')</h5>

    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>

<div class="modal-body">
    <div class="portlet-body">
        <x-form id="createFeature" method="POST" class="ajax-form">
            <div class="form-group">
                <div class="row">
                   
                <div class="col-lg-12">
                                <x-forms.select fieldId="feature_id" fieldName="feature_id"
                                    :fieldLabel="__('Feature')">
                                    <option value="">--</option>
                                    @foreach ($feature as $f)
                                        <option value="{{ $f->id }}">{{ $f->title }}</option>
                                    @endforeach
                                </x-forms.select>
                            </div>
                   
                    <div class="col-lg-12">
                        <x-forms.text :fieldLabel="__('app.title')" fieldName="title" autocomplete="off" fieldId="title" fieldRequired="true"/>
                    </div>
                        <div class="col-md-12">
                            <div class="form-group my-3">
                                <x-forms.label fieldId="description" :fieldLabel="__('app.description')">
                                </x-forms.label>
                                <div id="description"></div>
                                <textarea name="description" id="description_text" class="d-none"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <x-forms.file allowedFileExtensions="png jpg jpeg svg" class="mr-lg-2 mr-md-2 mr-0"
                                :fieldLabel="__('superadmin.types.image') . ' (400x352)'" fieldName="image" fieldId="image" :popover="__('superadmin.featureImageSizeMessage')" fieldRequired="true">
                            </x-forms.file>
                        </div>
                        <div class="col-lg-12">
                        <x-forms.text :fieldLabel="__('Video Link')" fieldName="video" autocomplete="off" fieldId="video" />
                    </div>

                    </div>
      
                </div>
            </div>
        </x-form>
    </div>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="save-feature" icon="check">@lang('app.save')</x-forms.button-primary>
</div>

<script src="{{ asset('vendor/iconpicker-master/dist/iconpicker.js') }}"></script>

<script>

    $(".select-picker").selectpicker();

    @if($type != 'apps')
        $(document).ready(function() {
            quillImageLoad('#description');

        });
    @endif

    $('#save-feature').click(function(event) {
        @if($type != 'apps')
        document.getElementById('description_text').value = document.getElementById('description').children[0]
            .innerHTML;
        @endif

        $.easyAjax({
            url: "{{ route('superadmin.front-settings.FrontFeatureReadMoreDetails.store') }}",
            container: '#createFeature',
            type: "POST",
            blockUI: true,
            file: true,
            data: $('#createFeature').serialize(),
            success: function(response) {
                if (response.status == "success") {
                    window.location.href = "{{ route('superadmin.front-settings.fetature.readMore-details') }}";
                    $(MODAL_LG).modal('hide');
                }
            }
        })
    });

  

    init('#createFeature');

</script>
