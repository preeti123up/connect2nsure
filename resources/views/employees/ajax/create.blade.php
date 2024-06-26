@php
$addDesignationPermission = user()->permission('add_designation');
@endphp

<link rel="stylesheet" href="{{ asset('vendor/css/tagify.css') }}">

<div class="row">
    <div class="col-sm-12">
        <x-form id="save-employee-data-form">

            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('modules.employees.accountDetails')</h4>
                <div class="row p-20">
                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-lg-2 col-md-3">
                                <x-forms.text fieldId="employee_id" :fieldLabel="__('modules.employees.employeeId')"
                                    fieldName="employee_id" :fieldValue="((!$checkifExistEmployeeId) ? ($lastEmployeeID+1) : '')" fieldRequired="true"
                                    :fieldPlaceholder="__('modules.employees.employeeIdInfo')" :popover="__('modules.employees.employeeIdHelp')">
                                </x-forms.text>
                            </div>
                            <div class="col-lg-2 col-md-3">
                                <x-forms.select fieldId="salutation" fieldName="salutation"
                                    :fieldLabel="__('modules.client.salutation')">
                                    <option value="">--</option>
                                    @foreach ($salutations as $salutation)
                                        <option value="{{ $salutation->value }}">{{ $salutation->label() }}</option>
                                    @endforeach
                                </x-forms.select>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <x-forms.text fieldId="name" :fieldLabel="__('modules.employees.employeeName')"
                                    fieldName="name" fieldRequired="true" :fieldPlaceholder="__('placeholders.name')">
                                </x-forms.text>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <x-forms.text fieldId="email" :fieldLabel="__('modules.employees.employeeEmail')"
                                    fieldName="email" fieldRequired="true" :fieldPlaceholder="__('placeholders.email')">
                                </x-forms.text>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <x-forms.datepicker fieldId="date_of_birth" :fieldLabel="__('modules.employees.dateOfBirth')"
                                    fieldName="date_of_birth" :fieldPlaceholder="__('placeholders.date')" />
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <x-forms.label class="my-3" fieldId="category_id"
                                    :fieldLabel="__('app.designation')" fieldRequired="true">
                                </x-forms.label>
                                <x-forms.input-group>
                                    <select class="form-control select-picker" name="designation"
                                        id="employee_designation" data-live-search="true">
                                        <option value="">--</option>
                                        @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                        @endforeach
                                    </select>
                                </x-forms.input-group>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <x-forms.label class="my-3" fieldId="category_id"
                                    :fieldLabel="__('app.department')" fieldRequired="true">
                                </x-forms.label>
                                <x-forms.input-group>
                                    <select class="form-control select-picker" name="department"
                                        id="employee_department" data-live-search="true">
                                        <option value="">--</option>
                                        @foreach ($teams as $team)
                                            <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                                        @endforeach
                                    </select>
                                </x-forms.input-group>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <x-forms.file allowedFileExtensions="png jpg jpeg svg bmp" class="mr-0 mr-lg-2 mr-md-2 cropper"
                            :fieldLabel="__('modules.profile.profilePicture')" fieldName="image" fieldId="image"
                            fieldHeight="119" :popover="__('messages.fileFormat.ImageFile')" />
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <x-forms.select fieldId="country" :fieldLabel="__('app.country')" fieldName="country"
                            search="true">
                            @foreach ($countries as $item)
                                @if($item->nicename=='India')
                                <option data-tokens="{{ $item->iso3 }}" data-phonecode = "{{$item->phonecode}}"
                                    data-content="<span class='flag-icon flag-icon-{{ strtolower($item->iso) }} flag-icon-squared'></span> {{ $item->nicename }}"
                                    value="{{ $item->id }}" selected >{{ $item->nicename }}</option>
                              @else

                                <!--<option data-tokens="{{ $item->iso3 }}" data-phonecode = "{{$item->phonecode}}"-->
                                <!--data-content="<span class='flag-icon flag-icon-{{ strtolower($item->iso) }} flag-icon-squared'></span> {{ $item->nicename }}"-->
                                <!--value="{{ $item->id }}">{{ $item->nicename }}</option>-->

                              @endif
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <x-forms.label class="my-3" fieldId="mobile"
                            :fieldLabel="__('app.mobile')" fieldRequired="true"></x-forms.label>
                        <x-forms.input-group style="margin-top:-4px">


                            <x-forms.select fieldId="country_phonecode" fieldName="country_phonecode"
                                search="true">

                                @foreach ($countries as $item)
                                      @if($item->phonecode=='91')
                                    <option data-tokens="{{ $item->name }}"
                                            data-content="{{$item->flagSpanCountryCode()}}"
                                            value="{{ $item->phonecode }}" selected>{{ $item->phonecode }}
                                    </option>
                                  @else
                                    <!--<option data-tokens="{{ $item->name }}"-->
                                    <!--    data-content="{{$item->flagSpanCountryCode()}}"-->
                                    <!--    value="{{ $item->phonecode }}">{{ $item->phonecode }}-->
                                    <!--</option>-->
                                  @endif
                                @endforeach
                            </x-forms.select>

                            <input type="tel" class="form-control height-35 f-14" placeholder="@lang('placeholders.mobile')"
                                name="mobile" id="mobile">
                        </x-forms.input-group>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <x-forms.select fieldId="gender" :fieldLabel="__('modules.employees.gender')"
                            fieldName="gender">
                            <option value="male">@lang('app.male')</option>
                            <option value="female">@lang('app.female')</option>
                            <option value="others">@lang('app.others')</option>
                        </x-forms.select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <x-forms.datepicker fieldId="joining_date" :fieldLabel="__('modules.employees.joiningDate')"
                            fieldName="joining_date" :fieldPlaceholder="__('placeholders.date')" fieldRequired="true"
                            :fieldValue="now(company()->timezone)->format(company()->date_format)" />
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <x-forms.select fieldId="reporting_to" :fieldLabel="__('modules.employees.reportingTo')"
                            fieldName="reporting_to" :fieldPlaceholder="__('placeholders.date')" search="true">
                            <option value="">--</option>
                            @foreach ($employees as $item)
                                <x-user-option :user="$item" />
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <x-forms.select fieldId="locale" :fieldLabel="__('app.language')"
                            fieldName="locale" search="true">
                            @foreach ($languages as $language)
                                <option {{ user()->locale == $language->language_code ? 'selected' : '' }}
                                data-content="<span class='flag-icon flag-icon-{{ ($language->flag_code == 'en') ? 'gb' : $language->flag_code }} flag-icon-squared'></span> {{ $language->language_name }}"
                                value="{{ $language->language_code }}">{{ $language->language_name }}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <x-forms.select fieldId="role" :fieldLabel="__('app.role')" fieldName="role">
                            @foreach ($roles as $role)
                             @if($role->name!="admin")
                                <option {{ ($role->name == 'employee') ? 'selected' : '' }} value="{{ $role->id }}">{{ $role->display_name }}</option>
                                @endif
                            @endforeach
                        </x-forms.select>
                    </div>
                    
                    @if($branches->count()>0)
                        <div class="col-lg-3 col-md-6">
                            <x-forms.select fieldId="assign_branch" :fieldLabel="__('Assign Branch')"  fieldName="assign_branch[]" multiple>
                                @php
                                  $i=0;
                                @endphp
                                  @foreach ($branches as $branch)
                                      <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                  @php
                                      $i++;
                                   @endphp
                                  @endforeach
                              </x-forms.select>
                        </div>
                    @endif
                    <div class="col-lg-3 col-md-6">
                    <div class="form-group my-3">
                            <label class="f-14 text-dark-grey mb-12 w-100"
                                for="usr">OOD Location</label>
                            <div class="d-flex">
                                <x-forms.radio fieldId="ood-yes" :fieldLabel="__('app.enable')" fieldName="ood"
                                    fieldValue="enable">
                                </x-forms.radio>
                                <x-forms.radio fieldId="ood-no" :fieldLabel="__('app.disable')"  fieldValue="disable" checked="true"
                                    fieldName="ood"></x-forms.radio>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group my-3">
                            <x-forms.textarea class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('app.address')"
                                fieldName="address" fieldId="address" :fieldPlaceholder="__('placeholders.address')">
                            </x-forms.textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group my-3">
                            <x-forms.textarea class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('app.about')"
                                fieldName="about_me" fieldId="about_me" fieldPlaceholder="">
                            </x-forms.textarea>
                        </div>
                    </div>

                </div>

 <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-top-grey">
                   Bank Details</h4>
                   
                    <div class="row p-20">
                       <div class="col-lg-4 col-md-6">
                <x-forms.label class="my-3" fieldId="bankName"
                                       :fieldLabel="__('Bank Name')" fieldRequired="false">
                        </x-forms.label>
                        <x-forms.input-group>
                            <select class="form-control select-picker" name="bank_name" id="bank_name"
                                    data-live-search="true">
                                <option value="">--</option>
                                @foreach($bankNames as $n)
                                 <option value="{{$n->name}}">{{$n->name}}</option>
                                @endforeach
                            </select>
                                <x-slot name="append">
                                    <button id="add-field"" type="button"
                                            class="btn btn-outline-secondary border-grey"
                                            data-toggle="tooltip" data-original-title="{{__('Add Bank Name') }}">@lang('app.add')</button>
                                </x-slot>
                        </x-forms.input-group>
                        
                </div>
                    <div class="col-lg-4 col-md-6">
                        <x-forms.text fieldId="account_no" :fieldLabel="__('Account Number')"
                            fieldName="account_no" >
                        </x-forms.text>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <x-forms.text fieldId="ifsc_code" :fieldLabel="__('IFSC Code')"
                            fieldName="ifsc_code" >
                        </x-forms.text>
                    </div>
                    <div class="col-lg-4 col-md-6">
                                <x-forms.text fieldId="UAN_no" :fieldLabel="__('UAN No. ')"
                                    fieldName="UAN_no">
                                </x-forms.text>
                            </div>
                    <div class="col-lg-4 col-md-6">
                        <x-forms.text fieldId="PF_no" :fieldLabel="__('PF No. ')"
                            fieldName="PF_no" >
                        </x-forms.text>
                    </div>

                  
                </div>
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-top-grey">
                    @lang('modules.client.clientOtherDetails')</h4>
                <div class="row p-20">
                    <div class="col-lg-3 col-md-6">
                        <div class="form-group my-3">
                            <label class="f-14 text-dark-grey mb-12 w-100"
                                for="usr">@lang('modules.client.clientCanLogin')</label>
                            <div class="d-flex">
                                <x-forms.radio fieldId="login-yes" :fieldLabel="__('app.yes')" fieldName="login"
                                    fieldValue="enable" checked="true">
                                </x-forms.radio>
                                <x-forms.radio fieldId="login-no" :fieldLabel="__('app.no')" fieldValue="disable"
                                    fieldName="login"></x-forms.radio>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="form-group my-3">
                            <label class="f-14 text-dark-grey mb-12 w-100"
                                for="usr">@lang('modules.emailSettings.emailNotifications')</label>
                            <div class="d-flex">
                                <x-forms.radio fieldId="notification-yes" :fieldLabel="__('app.yes')" fieldValue="yes"
                                    fieldName="email_notifications" checked="true">
                                </x-forms.radio>
                                <x-forms.radio fieldId="notification-no" :fieldLabel="__('app.no')" fieldValue="no"
                                    fieldName="email_notifications">
                                </x-forms.radio>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <x-forms.label class="my-3" fieldId="hourly_rate"
                            :fieldLabel="__('modules.employees.hourlyRate')"></x-forms.label>
                        <x-forms.input-group>
                            <x-slot name="prepend">
                                <span
                                    class="input-group-text f-14 bg-white-shade">{{ company()->currency->currency_symbol }}</span>
                            </x-slot>

                            <input type="number" step=".01" min="0" class="form-control height-35 f-14"
                                name="hourly_rate" id="hourly_rate">
                        </x-forms.input-group>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <x-forms.label class="my-3" fieldId="slack_username"
                            :fieldLabel="__('modules.employees.slackUsername')"></x-forms.label>
                        <x-forms.input-group>
                            <x-slot name="prepend">
                                <span class="input-group-text f-14 bg-white-shade">@</span>
                            </x-slot>

                            <input type="text" class="form-control height-35 f-14" name="slack_username"
                                id="slack_username">
                        </x-forms.input-group>
                    </div>

                    <div class="col-md-12">
                        <x-forms.text fieldId="tags" :fieldLabel="__('app.skills')" fieldName="tags"
                            :fieldPlaceholder="__('placeholders.skills')" />
                    </div>

                    @if (function_exists('sms_setting') && sms_setting()->telegram_status)
                        <div class="col-md-6">
                            <x-forms.number fieldName="telegram_user_id" fieldId="telegram_user_id"
                                fieldLabel="<i class='fab fa-telegram'></i> {{ __('sms::modules.telegramUserId') }}"
                                :popover="__('sms::modules.userIdInfo')" />
                            <p class="text-bold text-danger">
                                @lang('sms::modules.telegramBotNameInfo')
                            </p>
                            <p class="text-bold"><span id="telegram-link-text">https://t.me/{{ sms_setting()->telegram_bot_name }}</span>
                                <a href="javascript:;" class="btn-copy btn-secondary f-12 rounded p-1 py-2 ml-1"
                                    data-clipboard-target="#telegram-link-text">
                                    <i class="fa fa-copy mx-1"></i>@lang('app.copy')</a>
                                <a href="https://t.me/{{ sms_setting()->telegram_bot_name }}" target="_blank" class="btn-secondary f-12 rounded p-1 py-2 ml-1">
                                    <i class="fa fa-copy mx-1"></i>@lang('app.openInNewTab')</a>
                            </p>
                        </div>
                    @endif
                    <div class="col-lg-3 col-md-6">
                        <x-forms.datepicker fieldId="probation_end_date" :fieldLabel="__('modules.employees.probationEndDate')"
                            fieldName="probation_end_date" :fieldPlaceholder="__('placeholders.date')"
                            :popover="__('messages.probationEndDate')"/>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <x-forms.datepicker fieldId="notice_period_start_date" :fieldLabel="__('modules.employees.noticePeriodStartDate')"
                            fieldName="notice_period_start_date" :fieldPlaceholder="__('placeholders.date')"
                            :popover="__('messages.noticePeriodStartDate')"/>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <x-forms.datepicker fieldId="notice_period_end_date" :fieldLabel="__('modules.employees.noticePeriodEndDate')"
                            fieldName="notice_period_end_date" :fieldPlaceholder="__('placeholders.date')"
                            :popover="__('messages.noticePeriodEndDate')"/>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <x-forms.select fieldId="employment_type" :fieldLabel="__('modules.employees.employmentType')"
                            fieldName="employment_type" :fieldPlaceholder="__('placeholders.date')">
                            <option value="">--</option>
                            <option value="full_time">@lang('app.fullTime')</option>
                            <option value="part_time">@lang('app.partTime')</option>
                            <option value="on_contract">@lang('app.onContract')</option>
                            <option value="internship">@lang('app.internship')</option>
                            <option value="trainee">@lang('app.trainee')</option>
                        </x-forms.select>
                    </div>

                    <div class="col-lg-3 col-md-6 d-none internship-date">
                        <x-forms.datepicker fieldId="internship_end_date" :fieldLabel="__('modules.employees.internshipEndDate')"
                            fieldName="internship_end_date" :fieldPlaceholder="__('placeholders.date')"/>
                    </div>
                    <div class="col-lg-3 col-md-6 d-none contract-date">
                        <x-forms.datepicker fieldId="contract_end_date" :fieldLabel="__('modules.employees.contractEndDate')"
                            fieldName="contract_end_date" :fieldPlaceholder="__('placeholders.date')"/>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <x-forms.select fieldId="marital_status" :fieldLabel="__('modules.employees.maritalStatus')"
                            fieldName="marital_status" :fieldPlaceholder="__('placeholders.date')">
                            <option value="unmarried">@lang('modules.leaves.unmarried')</option>
                            <option value="married">@lang('modules.leaves.married')</option>
                        </x-forms.select>
                    </div>

                    <div class="col-lg-3 col-md-6 d-none marriage_date">
                        <x-forms.datepicker fieldId="marriage_anniversary_date" :fieldLabel="__('modules.employees.marriageAnniversaryDate')"
                            fieldName="marriage_anniversary_date" :fieldPlaceholder="__('placeholders.date')"/>
                    </div>

                    <input type ="hidden" name="add_more" value="false" id="add_more" />

                </div>
                <x-forms.custom-field :fields="$fields"></x-forms.custom-field>

                <x-form-actions>
                    <x-forms.button-primary id="save-employee-form" class="mr-3" icon="check">
                        @lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-secondary class="mr-3" id="save-more-employee-form" icon="check-double">@lang('app.saveAddMore')
                    </x-forms.button-secondary>
                    <x-forms.button-cancel class="border-0 " data-dismiss="modal">@lang('app.cancel')
                    </x-forms.button-cancel>

                </x-form-actions>
            </div>
        </x-form>

    </div>
</div>

<script src="{{ asset('vendor/jquery/tagify.min.js') }}"></script>
@if (function_exists('sms_setting') && sms_setting()->telegram_status)
    <script src="{{ asset('vendor/jquery/clipboard.min.js') }}"></script>
@endif
<script>
     $('#mobile').on('input', function () {
        var maxlength = 10;
        if (this.value.length > maxlength) {
            this.value = this.value.slice(0, maxlength); // Trim the input to the first 10 characters
        }
    });
     $('#account_no').on('input', function () {
    var value = this.value.replace(/\D/g, ''); // Remove non-numeric characters
    if (value.length > 18) {
        // If the length of the value exceeds 18 digits, truncate it to 18 digits
        value = value.slice(0, 18);
    }
    this.value = value;
});
    $('#UAN_no').on('input', function () {
    var value = this.value.replace(/\D/g, ''); // Remove non-numeric characters
    if(value.length > 12){
       value=value.slice(0,12);
    }
    this.value = value;
});
 ;
    $('#ifsc_code').on('input', function () {
    var value = this.value // Remove non-numeric characters
    if(value.length > 11){
       value=value.slice(0,11);
    }
    this.value = value;
});
</script>
<script>
    $(document).ready(function() {
            function subtractYears(date, years) {
             date.setFullYear(date.getFullYear() - years);
             return date;
           }
        var year= new Date();
        var newDate=subtractYears(year,18);
        var id=$('#employee_id').val();
        var empId = 'SL00' + String(id).padStart(3, '0');
        $('#employee_id').val(empId);
       
        
       $('#employee_id').val(empId);
        $('.custom-date-picker').each(function(ind, el) {
            datepicker(el, {
                position: 'bl',
                ...datepickerConfig
            });
        });

        datepicker('#joining_date', {
            position: 'bl',
            ...datepickerConfig
        });

        datepicker('#probation_end_date', {
            position: 'bl',
            ...datepickerConfig
        });

        datepicker('#notice_period_start_date', {
            position: 'bl',
            ...datepickerConfig
        });

        datepicker('#notice_period_end_date', {
            position: 'bl',
            ...datepickerConfig
        });

        datepicker('#marriage_anniversary_date', {
            position: 'bl',
            ...datepickerConfig
        });

        datepicker('#date_of_birth', {
            position: 'bl',
            maxDate: newDate,
            ...datepickerConfig
        });

        datepicker('#internship_end_date', {
            position: 'bl',
            ...datepickerConfig
        });

        datepicker('#contract_end_date', {
            position: 'bl',
            ...datepickerConfig
        });

        $('#marital_status').change(function(){
            var value = $(this).val();
            if(value == 'married') {
                $('.marriage_date').removeClass('d-none');
            }
            else {
                $('.marriage_date').addClass('d-none');
            }
        })

        $('#employment_type').change(function(){
            var value = $(this).val();
            if(value == 'on_contract') {
                $('.contract-date').removeClass('d-none');
            }
            else {
                $('.contract-date').addClass('d-none');
            }

            if(value == 'internship') {
                $('.internship-date').removeClass('d-none');
            }
            else {
                $('.internship-date').addClass('d-none');
            }
        })
        var input = document.querySelector('input[name=tags]'),
            // init Tagify script on the above inputs
            tagify = new Tagify(input);

        $('#save-more-employee-form').click(function() {

            $('#add_more').val(true);

            const url = "{{ route('employees.store') }}";
            var data = $('#save-employee-data-form').serialize();
            saveEmployee(data, url, "#save-more-employee-form");


        });

        $('#save-employee-form').click(function() {

            const url = "{{ route('employees.store') }}";
            var data = $('#save-employee-data-form').serialize();
            saveEmployee(data, url, "#save-employee-form");

        });

        function saveEmployee(data, url, buttonSelector) {
            $.easyAjax({
                url: url,
                container: '#save-employee-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: buttonSelector,
                file: true,
                data: data,
                success: function(response) {
                    if (response.status == 'success') {
                        if ($(MODAL_XL).hasClass('show')) {
                            $(MODAL_XL).modal('hide');
                            window.location.reload();
                        }
                        else if(response.add_more == true) {

                            var right_modal_content = $.trim($(RIGHT_MODAL_CONTENT).html());

                            if(right_modal_content.length) {

                                $(RIGHT_MODAL_CONTENT).html(response.html.html);
                                $('#add_more').val(false);
                            }
                            else {

                                $('.content-wrapper').html(response.html.html);
                                init('.content-wrapper');
                                $('#add_more').val(false);
                            }

                        }
                        else {

                            window.location.href = response.redirectUrl;

                        }

                        if (typeof showTable !== 'undefined' && typeof showTable === 'function') {
                            showTable();
                        }

                    }

                }
            });
        }

        $('#random_password').click(function() {
            const randPassword = Math.random().toString(36).substr(2, 8);

            $('#password').val(randPassword);
        });

        $('#designation-setting-add').click(function() {
            const url = "{{ route('designations.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        })

        $('.department-setting').click(function() {
            const url = "{{ route('departments.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        $('#country').change(function(){
            var phonecode = $(this).find(':selected').data('phonecode');
            $('#country_phonecode').val(phonecode);
            $('.select-picker').selectpicker('refresh');
        });


        init(RIGHT_MODAL);
    });

    function checkboxChange(parentClass, id) {
        var checkedData = '';
        $('.' + parentClass).find("input[type= 'checkbox']:checked").each(function() {
            checkedData = (checkedData !== '') ? checkedData + ', ' + $(this).val() : $(this).val();
        });
        $('#' + id).val(checkedData);
    }

    $('.cropper').on('dropify.fileReady', function(e) {
        var inputId = $(this).find('input').attr('id');
        var url = "{{ route('cropper', ':element') }}";
        url = url.replace(':element', inputId);
        $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
        $.ajaxModal(MODAL_LG, url);
    })

    @if (function_exists('sms_setting') && sms_setting()->telegram_status)
        var clipboard = new ClipboardJS('.btn-copy');

        clipboard.on('success', function(e) {
            Swal.fire({
                icon: 'success',
                text: '@lang("app.urlCopied")',
                toast: true,
                position: 'top-end',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                customClass: {
                    confirmButton: 'btn btn-primary',
                },
                showClass: {
                    popup: 'swal2-noanimation',
                    backdrop: 'swal2-noanimation'
                },
            })
        });
    @endif
</script>
<script>
     $('body').on('click', '#add-field', function () {
            const url = "{{ route('bank-name.create')}}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
    });
</script>

