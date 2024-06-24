@extends('layouts.app')

@section('content')

    <!-- SETTINGS START -->
    <div class="w-100 d-flex ">

        @include('sections.setting-sidebar')

        <x-setting-card>
            <x-slot name="header">
                <div class="s-b-n-header" id="tabs">
                    <h2 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                        @lang($pageTitle)</h2>
                </div>
            </x-slot>

            <div class="col-lg-12 col-md-12 ntfcn-tab-content-left w-100 p-4 ">
                @method('PUT')
                <div class="row">
                    <div class="col-lg-6">
                        <x-forms.text class="mr-0 mr-lg-2 mr-md-2"
                                      :fieldLabel="__('modules.accountSettings.companyName')"
                                      :fieldPlaceholder="__('placeholders.company')" fieldRequired="true"
                                      fieldName="company_name"
                                      :popover="__('messages.companyNameTooltip')"
                                      fieldId="company_name" :fieldValue="company()->company_name"/>
                    </div>
                    <div class="col-lg-6">
                        <x-forms.text class="mr-0 mr-lg-2 mr-md-2"
                                      :fieldLabel="__('modules.accountSettings.companyEmail')"
                                      :fieldPlaceholder="__('placeholders.email')" fieldRequired="true"
                                      fieldName="company_email"
                                      fieldId="company_email" :fieldValue="company()->company_email"/>

                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <x-forms.text class="mr-0 mr-lg-2 mr-md-2"
                                      :fieldLabel="__('modules.accountSettings.companyPhone')"
                                      :fieldPlaceholder="__('placeholders.mobileWithPlus')" fieldRequired="true" fieldName="company_phone"
                                      fieldId="company_phone" :fieldValue="company()->company_phone"/>
                    </div>
                    <div class="col-lg-6">
                        <x-forms.text class="mr-0 mr-lg-2 mr-md-2"
                                      :fieldLabel="__('modules.accountSettings.companyWebsite')"
                                      :fieldPlaceholder="__('placeholders.website')" fieldRequired="false"
                                      fieldName="website"
                                      fieldId="website" :fieldValue="company()->website"/>
                    </div>
                    
                </div>
                 <div class="row">
                    <div class="col-lg-6 col-md-6">
                       <x-forms.label class="my-3" fieldId="bankName"
                                       :fieldLabel="__('Bank Name')" fieldRequired="false">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker" name="bank_name" id="bank_name"
                                    data-live-search="true">
                                <option value="">--</option>
                               @foreach($bankNames as $n)
                                 <option value="{{$n->name}}" @if(company()->bank_name==="$n->name") selected @endif >{{$n->name}}</option>
                                @endforeach
                            </select>
                                <x-slot name="append">
                                    <button id="add-field"" type="button"
                                            class="btn btn-outline-secondary border-grey"
                                            data-toggle="tooltip" data-original-title="{{__('Add Bank Name') }}">@lang('app.add')</button>
                                </x-slot>
                        </x-forms.input-group>
                        
                    </div>
                    <div class="col-lg-6">
                        <x-forms.text class="mr-0 mr-lg-2 mr-md-2"
                                      :fieldLabel="__('Account Number')"
                                      :fieldPlaceholder="__('Account Number')" fieldRequired="false"
                                      fieldName="account_no"
                                      fieldId="account_no" :fieldValue="company()->account_no"/>
                    </div>
                    <div class="col-lg-6">
                        <x-forms.text class="mr-0 mr-lg-2 mr-md-2"
                                      :fieldLabel="__('IFSC Code')"
                                      :fieldPlaceholder="__('IFSC Code')" fieldRequired="false"
                                      fieldName="ifsc_code"
                                      fieldId="ifsc_code" :fieldValue="company()->ifsc_code"/>
                    </div>
                </div>

            </div>

            <x-slot name="action">
                <!-- Buttons Start -->
                <div class="w-100 border-top-grey">
                    <x-setting-form-actions>
                        <x-forms.button-primary id="save-form" class="mr-3" icon="check">@lang('app.save')
                        </x-forms.button-primary>
                        </x-settingsform-actions>
                </div>
                <!-- Buttons End -->
            </x-slot>

        </x-setting-card>

    </div>
    <!-- SETTINGS END -->
@endsection

@push('scripts')
    <script>
        $('#save-form').click(function () {
            var url = "{{ route('company-settings.update', company()->id) }}";

            $.easyAjax({
                url: url,
                container: '#editSettings',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#save-form",
                data: $('#editSettings').serialize(),
            })
        });
    </script>
        <script>
    
    $('#account_no').on('input', function () {
    var value = this.value.replace(/\D/g, ''); // Remove non-numeric characters
    if (value.length > 18) {
        // If the length of the value exceeds 18 digits, truncate it to 18 digits
        value = value.slice(0, 18);
    }
    this.value = value;
});

    $('#ifsc_code').on('input', function () {
    var value = this.value // Remove non-numeric characters
    if(value.length > 11){
       value=value.slice(0,11);
    }
    this.value = value;
});
</script>
<script>
     $('body').on('click', '#add-field', function () {
            const url = "{{ route('bank-name.create')}}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
    });
</script>
@endpush
