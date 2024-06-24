<div class="row">
    <div class="col-sm-12">
        <x-form id="save-designation-data-form">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('Add Reimbursement')</h4>
                <div class="row p-20">
                    <div class="col-md-6">
                        <x-forms.select fieldId="user_id" :fieldLabel="__('Employees')" fieldName="user_id"  fieldRequired="true">
                                <option value="">---</option>
                                @foreach($user as $u)
                                    <option value="{{$u->id}}">{{$u->name}}</option>
                                @endforeach
                        </x-forms.select>
                  </div>
                    <div class="col-md-6">
                       
                            <x-forms.datepicker class="date-picker" :fieldLabel="__('Date of expense')" fieldName="date_expense" fieldId="date_expense"
                                :fieldPlaceholder="__('Date of expense')"  fieldRequired="true" />
                   
                    </div>
                  
                    <div class="col-md-6">
                        <x-forms.text fieldId="designation_amount" :fieldLabel="__('Amount')" fieldName="amount"
                                      fieldRequired="true" >
                        </x-forms.text>
                    </div>  
                    <div class="col-md-6">
                        <x-forms.label class="mt-3" fieldId="parent_label" :fieldLabel="__('Paymount Mode')"
                                       fieldName="parent_label">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker" name="payment" id="payment"
                                    data-live-search="true">
                                <option value="">--</option>
                                
                                    <option value="cash">Cash</option>
                                    <option value="online">Online</option>
                              
                            </select>
                        </x-forms.input-group>
                       
                    </div>
                    <div class="col-md-6">
                        <x-forms.label class="mt-3" fieldId="type_expense" :fieldLabel="__('Expense Type')"
                                       fieldName="parent_label">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker" name="expense" id="expense"
                                    data-live-search="true">
                                <option value="">--</option>
                                    <option value="Air/Rail Travel">Air/Rail Travel</option>
                                    <option value="Ground Transport">Ground Transport</option>
                                     <option value="Lodging">Lodging</option>
                                     <option value="Meals">Meals</option>
                                   
                            </select>
                        </x-forms.input-group>
                    </div>
                    <div class="col-md-6">
                            <x-forms.text fieldId="purpose" :fieldLabel="__('Purpose of Expenditure')" fieldName="purpose"
                                 fieldRequired="true" >
                             </x-forms.text>
                       
                    </div>
                   

                    <div class="col-lg-6">
                        <x-forms.file allowedFileExtensions="png jpg jpeg svg bmp" class="mr-0 mr-lg-2 mr-md-2 cropper"
                            :fieldLabel="__('Image')" fieldName="image" fieldId="image"
                            fieldHeight="119" :popover="__('messages.fileFormat.ImageFile')" />
                    </div>
                </div>

                <x-form-actions>
                    <x-forms.button-primary id="save-designation-form" class="mr-3" icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-cancel :link="route('designations.index')" class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-form-actions>

            </div>
        </x-form>

    </div>
</div>

<script>
    $(document).ready(function () {

    function validateDate(inputDate) {
        if(inputDate.value !== ''){
            var dateRegex = /^\d{2}-\d{2}-\d{4}$/;
    var isValid = dateRegex.test(inputDate.value);
    if (isValid) {
        inputDate.classList.remove('error');
    } else {
        inputDate.classList.add('error');
        inputDate.value = '';
    }
        }
   
}

        $('#save-designation-form').click(function () {

            const url = "{{ route('reimbursement.store') }}";

            $.easyAjax({
                url: url,
                container: '#save-designation-data-form',
                type: "POST",
                file:true,
                disableButton: true,
                blockUI: true,
                buttonSelector: "#save-designation-form",
                data: $('#save-designation-data-form').serialize(),
                success: function (response) {
                    if (response.status === 'success') {
                        if ($(MODAL_XL).hasClass('show')) {
                            $(MODAL_XL).modal('hide');
                            window.location.reload();
                        } else {
                            window.location.href='{{route('reimbursement.index')}}';
                        }
                    }
                }
            });
        });

        init(RIGHT_MODAL);
        datepicker('#date_expense', {
      
          
            ...datepickerConfig
        });
    });
 
</script>
