<div class="row">
    <div class="col-sm-12">
        <x-form id="save-lend-data-form">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    Return Asset</h4>
                <div class="row pl-20 pr-20 pt-20">
                        <input value="{{ $lednedAsset->id }}" name="id" type="hidden">
                    <div class="col-lg-6">
                        <x-forms.text class="date-picker" :fieldLabel="__('Date  of Return')" fieldName="return_date"
                            fieldId="return_date" :fieldPlaceholder="__('Select Date')" fieldRequired="true" />
                    </div>
                    <div class="col-lg-12">
                        <x-forms.textarea :fieldLabel="__('Notes')" fieldName="note" fieldId="note" fieldRequired="true" />
                    </div>
                </div>
                <x-form-actions>
                    <x-forms.button-primary id="save-return-form" class="mr-3" icon="check">@lang('app.save')
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
        const dp1 = datepicker('#return_date', {
            position: 'bl',
            ...datepickerConfig
        });
        $('#save-return-form').click(function() {

            const url = "{{ route('store.return.asset') }}";
            $.easyAjax({
                url: url,
                container: '#save-lend-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                file: true,
                buttonSelector: "#save-return-form",
                data: $('#save-lend-data-form').serialize(),
                success: function(response) {
                    window.location.href = response.redirectUrl;
                }
            });
        });

        
        init(RIGHT_MODAL);

    });
</script>
