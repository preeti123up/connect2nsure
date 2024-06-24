<div class="row">
    <div class="col-sm-12">
        <x-form id="update-asset" method="PUT">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('Edit Asset')</h4>
                <div class="row p-20">
                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-lg-6">
                                <x-forms.text :fieldLabel="__('Asset Name')" fieldName="asset_name" fieldId="asset_name"
                                    fieldRequired="true" :fieldValue="$asset->asset_name  " />
                                <span id="asset_name" data-name="asset_name" data-msg="asset_name"></span>
                            </div>
                            <div class="col-lg-6 col-md-6">
                            <x-forms.label class="my-3" fieldId="Asset Type"
                                       :fieldLabel="__('Asset Type')">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker" name="asset_type" id="asset_type"
                                    data-live-search="true">
                                <option value="">--</option>
                                @foreach ($device as $d)
                                <option value="{{$d->id}}" @if($d->id==$asset->asset_type) selected @endif>{{$d->name}}</option>
                                @endforeach
                            </select>
                                <x-slot name="append">
                                    <button id="add-field"" type="button"
                                            class="btn btn-outline-secondary border-grey"
                                            data-toggle="tooltip" data-original-title="{{__('Add Asset Type') }}">@lang('app.add')</button>
                                </x-slot>
                        </x-forms.input-group>
                        <!-- <x-forms.select fieldId="marital_status" :fieldLabel="__('Asset Type')"
                            fieldName="asset_type">
                            @foreach($device as $d)
                            <option value="{{$d->id}}">{{$d->name}}</option>
                            @endforeach
                        </x-forms.select> -->
                </div>

                            <div class="col-lg-6">
                                <x-forms.text :fieldLabel="__('Serial Number')" fieldName="serial_number"
                                    fieldId="serial_number" fieldRequired="true" :fieldValue="$asset->serial_number" />
                                <span id="serial_number" data-name="serial_number" data-msg="serial_number"></span>
                            </div>

                         
                            <div class="col-lg-6">
                                <x-forms.text :fieldLabel="__('Location')" fieldName="location" fieldId="location"
                                     :fieldValue="$asset->location" />
                                <span id="location" data-name="location" data-msg="branch location"></span>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group my-3">
                                    <label class="f-14 text-dark-grey mb-12 w-100" for="usr">Status</label>
                                    <div class="d-flex">
                                        <x-forms.radio fieldId="available" :fieldLabel="__('Available')"
                                            fieldName="status"
                                            :checked="($asset->status == 'Available') ? 'checked' : ''"
                                            fieldValue="Available">
                                        </x-forms.radio>
                                        <x-forms.radio fieldId="non_functional" :fieldLabel="__('Non-functional')"
                                            fieldValue="Non Functional"
                                            :checked="($asset->status == 'Non Functional') ? 'checked' : ''"
                                            fieldName="status"></x-forms.radio>
                                        <x-forms.radio fieldId="lost" :fieldLabel="__('Lost')" fieldValue="Lost"
                                            :checked="($asset->status == 'Lost') ? 'checked' : ''"
                                            fieldName="status"></x-forms.radio>
                                        <x-forms.radio fieldId="damaged" :fieldLabel="__('Damaged')"
                                            fieldValue="Damaged" checked="true" fieldName="status"
                                            :checked="($asset->status == 'Damaged') ? 'checked' : ''"></x-forms.radio>
                                        <x-forms.radio fieldId="under_maintance" :fieldLabel="__('Under Maintenance')"
                                            fieldValue="Under Maintenance" checked="true"
                                            :checked="($asset->status == 'Under Maintenance') ? 'checked' : ''"
                                            fieldName="status"></x-forms.radio>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="col-lg-3">
                        <x-forms.file  class="mr-0 mr-lg-2 mr-md-2 cropper"
                            :fieldLabel="__('Asset Picture')" fieldName="image" fieldId="image" fieldHeight="119"  :fieldValue="asset('/user-uploads/asset/'.$asset->asset_picture)"
                            :popover="__('messages.fileFormat.ImageFile')" />
                    </div>

                    <div class="col-lg-12">
                        <x-forms.text :fieldLabel="__('Description')" fieldName="description" fieldId="description"
                            fieldRequired="true" :fieldValue="$asset->description" />
                        <span id="description" data-name="description" data-msg="description"></span>
                    </div>
                    <x-form-actions>
                        <x-forms.button-primary id="update" class="mr-3" icon="check">@lang('app.save')
                        </x-forms.button-primary>
                        <x-forms.button-cancel :link="route('asset.index')" class="border-0">@lang('app.cancel')
                        </x-forms.button-cancel>
                    </x-form-actions>
                </div>
        </x-form>

    </div>
</div>
<script>
$(document).ready(function() {
    $('#serial_number,#asset_name').on('input', function() {
        var inputValue = $(this).val();
        var alphanumericRegex = /^[a-zA-Z0-9_-]+$/;

        if (!alphanumericRegex.test(inputValue)) {
            $(this).val(inputValue.replace(/[^a-zA-Z0-9_-]/g, '')); // Remove special characters from input
        }
    });
});
</script>
<script>
    $(document).ready(function () {

        $('#update').click(function (e) {
            const url = "{{ route('asset.update', $asset->id) }}";
            console.log(url);
            $.easyAjax({
                url: url,
                container: '#update-asset',
                type: "POST",
                disableButton: true,
                blockUI: true,
                file:true,
                buttonSelector: "#update",
                data: $('#update-asset').serialize(),
                success: function (response) {
                    window.location.href = response.redirectUrl;
                }
            });
        });

        init(RIGHT_MODAL);
    });
    $('body').on('click', '#add-field', function () {
            const url = "{{ route('devices.create')}}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
    });
</script>