<div class="row">
    <div class="col-sm-12">
        <x-form id="save-lend-data-form">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    Lend Assets</h4>
                <div class="row pl-20 pr-20 pt-20">
                    <div class="col-lg-4">
                        <input value="{{ $asset->id }}" name="asset_id" type="hidden">
                        <x-forms.select fieldId="user_id" :fieldLabel="__('modules.messages.chooseMember')" fieldName="user_id" search="true"
                            fieldRequired="true">
                            <option value="">--</option>
                            @foreach ($employees as $employee)
                                <x-user-option :user="$employee" :selected="request()->has('default_assign') &&
                                    request('default_assign') == $employee->id" />
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-lg-4">
                        <x-forms.text class="date-picker" :fieldLabel="__('Allocation date')" fieldName="date_given" fieldId="date_given"
                            :fieldPlaceholder="__('Select Date')" fieldRequired="true" />
                    </div>
                    <div class="col-lg-4">
                        <x-forms.text class="date-picker" :fieldLabel="__('Estimated Date  of Return')" fieldName="date_return"
                            fieldId="date_return" :fieldPlaceholder="__('Select Date')" fieldRequired="true" />
                    </div>
                    <div class="col-lg-12">
                        <x-forms.textarea :fieldLabel="__('Notes')" fieldName="note" fieldId="note" fieldRequired="true" />
                    </div>
                </div>
                <x-form-actions>
                    <x-forms.button-primary id="save-lend-form" class="mr-3" icon="check">@lang('app.save')
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
        const dp1 = datepicker('#date_given', {
            position: 'bl',
            ...datepickerConfig
        });

        const dp2 = datepicker('#date_return', {
    position: 'bl',
    minDate: new Date(), // Set the minimum date to today's date
    ...datepickerConfig
});

        $('#save-lend-form').click(function() {

            const url = "{{ route('asset.storeLend') }}";
            $.easyAjax({
                url: url,
                container: '#save-lend-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                file: true,
                buttonSelector: "#save-lend-form",
                data: $('#save-lend-data-form').serialize(),
                success: function(response) {
                    window.location.href = response.redirectUrl;
                }
            });
        });

        init(RIGHT_MODAL);

    });
</script>
