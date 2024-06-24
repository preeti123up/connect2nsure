@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatable_css')
@endpush

@section('filter-section')

    <x-filters.filter-box>

        <!-- DESIGNATION START -->
        <div class="select-box d-flex py-2 pr-lg-3 pr-md-3 px-0 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('app.select') @lang('app.year')</p>
            <div class="select-status">
                <select class="form-control select-picker" name="year" id="year">
                    @for($i = $year; $i >= ($year-4); $i--)
                        <option @if($i == $year) selected @endif value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <!-- DESIGNATION END -->
        <div class="select-box d-flex py-2 px-lg-3 px-md-3 px-0 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center"> @lang('payroll::modules.payroll.salaryCycle')</p>
            <div class="select-status">
                <select class="form-control select-picker" name="payroll_cycle" id="payrollCycle">
                    @foreach($payrollCycles as $payrollCycle)
                        <option value="{{ $payrollCycle->id }}"> {{ __('payroll::modules.payroll.'.$payrollCycle->cycle )}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="select-box d-flex py-2 px-lg-3 px-md-3 px-0 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center"
               id="select-label">@lang('app.select') @lang('app.month')</p>
            <div class="select-status">
                <select class="form-control select-picker" name="month" id="month">
                </select>
            </div>
        </div>
        
        <!-- SEARCH BY TASK START -->
        <div class="task-search d-flex  py-1 px-lg-3 px-0 border-right-grey align-items-center">
            <form class="w-100 mr-1 mr-lg-0 mr-md-1 ml-md-1 ml-0 ml-lg-0">
                <div class="input-group bg-grey rounded">
                    <div class="input-group-prepend">
                        <span class="input-group-text border-0 bg-additional-grey">
                            <i class="fa fa-search f-13 text-dark-grey"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control f-14 p-1 border-additional-grey" id="search-text-field"
                           placeholder="@lang('app.startTyping')">
                </div>
            </form>
        </div>
        <!-- SEARCH BY TASK END -->

        <!-- RESET START -->
        <div class="select-box d-flex py-1 px-lg-2 px-md-2 px-0">
            <x-forms.button-secondary class="btn-xs d-none" id="reset-filters" icon="times-circle">
                @lang('app.clearFilters')
            </x-forms.button-secondary>
        </div>
        <!-- RESET END -->

    </x-filters.filter-box>

@endsection

@php
    $addPayrollPermission = user()->permission('add_payroll');
    $editPayrollPermission = user()->permission('edit_payroll');
@endphp

@section('content')
    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">

        @if($addPayrollPermission == 'all' || $addPayrollPermission == 'added')
            <div class="card bg-white border-0 b-shadow-4">
                <div class="card-header bg-white border-bottom-grey text-capitalize justify-content-between p-20">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="heading-h1 mb-3">@lang('payroll::modules.payroll.generate') @lang('payroll::app.menu.payroll')</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <x-form id="genrate-payroll-form" method="PUT">
                        <div class="row">

                            <div class="col-4 mb-2 firstRow">
                                <x-forms.checkbox fieldId="includeExpenseClaims" checked
                                                  :fieldLabel="__('payroll::modules.payroll.includeExpenseClaims')"
                                                  fieldName="includeExpenseClaims"/>
                            </div>
                            <div class="col-4 mb-2 firstRow">
                                <x-forms.checkbox fieldId="addTimelogs"
                                                  :fieldLabel="__('payroll::modules.payroll.addTimelogs')"
                                                  fieldName="addTimelogs"/>
                            </div>
                            <div class="col-4 mb-2 firstRow">
                                <x-forms.checkbox fieldId="useAttendance" checked
                                                  :fieldLabel="__('payroll::modules.payroll.useAttendance')"
                                                  fieldName="useAttendance"/>
                            </div>
                            {{-- <div class="col-4 mb-4 useAttendanceBox">
                                <x-forms.checkbox fieldId="mark_leaves_paid" checked
                                                  :fieldLabel="__('payroll::modules.payroll.markApprovedLeavesPaid')"
                                                  fieldName="mark_leaves_paid"/>
                            </div> --}}
                            <div class="col-4 mb-4 useAttendanceBox">
                                <x-forms.checkbox fieldId="mark_absent_unpaid"  checked
                                                  :fieldLabel="__('payroll::modules.payroll.markAbsentUnpaid')"
                                                  fieldName="mark_absent_unpaid"/>
                            </div>
                            <div class="w-100 border-top-grey d-flex justify-content-end px-4 py-3">
                                <div class="ml-3">
                                   <x-forms.button-secondary id="export-all-details" class="mr-3 mb-2 mb-lg-0 mr-2" icon="file-export">
                                    @lang('Export Bank Details')
                                </x-forms.button-secondary>
                                </div>
                                <x-forms.button-primary id="generate-payslip"
                                                        icon="paper-plane">@lang('payroll::modules.payroll.generate')
                                </x-forms.button-primary>
                                  <div class="ml-2">
                                   <x-forms.button-secondary id="export-all" class="mr-3 mb-2 mb-lg-0" icon="file-export">
                                    @lang('app.exportExcel')
                                </x-forms.button-secondary>
                                    </div>
                            </div>
                        </div>
                    </x-form>
                </div>
            </div>
        @endif


        <div class="d-flex mt-4 justify-content-end action-bar">

            <x-datatable.actions>
                <div class="select-status mr-3">
                    <select name="action_type" class="form-control select-picker" id="quick-action-type" disabled>
                        <option value="">@lang('app.selectAction')</option>
                        @if($editPayrollPermission == 'all' || $editPayrollPermission == 'added')
                            <option value="change-status">@lang('modules.tasks.changeStatus')</option>
                        @endif
                        @if($addPayrollPermission == 'all' || $addPayrollPermission == 'added')
                            <option value="regenerate-payslip">@lang('payroll::modules.payroll.regenerate')</option>
                        @endif
                    </select>
                </div>
            </x-datatable.actions>
        </div>
          <!-- Add Task Export Buttons Start -->
        <!--<div class="d-flex" id="table-actions">-->

        </div>
        <!-- Add Task Export Buttons End -->
        <!-- Task Box Start -->
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">

            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}

        </div>
        <!-- Task Box End -->
    </div>
    <!-- CONTENT WRAPPER END -->

@endsection

@push('scripts')
    @include('sections.datatable_js')

    <script>
        $('#useAttendance').change(function() {
            if($('#useAttendance').prop('checked')) {
                $('.useAttendanceBox').show();
                $( ".firstRow" ).removeClass( "mb-4" ).addClass( "mb-2" );
            } else {
                $( ".firstRow" ).removeClass( "mb-2" ).addClass( "mb-4" );
                $('.useAttendanceBox').hide();
            }
        });

        $('#payroll-table').on('preXhr.dt', function (e, settings, data) {

            var month = $('#month').val();
            var year = $('#year').val();
            var cycle = $('#payrollCycle').val();
            var searchText = $('#search-text-field').val();
            data['month'] = month;
            data['year'] = year;
            data['searchText'] = searchText;
            data['cycle'] = cycle;
        });
        const showTable = () => {
            window.LaravelDataTables["payroll-table"].draw(false);
        }

        $('#month, #year, #search-text-field').on('change keyup',

            function () {
                if ($('#month').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else if ($('#year').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else if ($('#search-text-field').val() != "") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else if ($('#payrollCycle').val() != "") {
                    $('#reset-filters').removeClass('d-none');
                    showTable();
                } else {
                    $('#reset-filters').addClass('d-none');
                    showTable();
                }
            });


        $('#reset-filters').click(function () {
            $('#filter-form')[0].reset();

            $('.filter-box .select-picker').selectpicker("refresh");
            $('#reset-filters').addClass('d-none');
            getCycleData();
        });

        $('#quick-action-type').change(function () {
            const actionValue = $(this).val();
            if (actionValue != '') {
                $('#quick-action-apply').removeAttr('disabled');

                if (actionValue == 'change-status') {
                    $('.quick-action-field').addClass('d-none');
                    $('#change-status-action').removeClass('d-none');
                } else {
                    $('.quick-action-field').addClass('d-none');
                }
            } else {
                $('#quick-action-apply').attr('disabled', true);
                $('.quick-action-field').addClass('d-none');
            }
        });

        $('#quick-action-apply').click(function () {
            const actionValue = $('#quick-action-type').val();
            if (actionValue == 'regenerate-payslip') {
                regeneratePayslip();
            } else {
                applyQuickAction();
            }
        });

        $('body').on('click', '.delete-table-row', function () {
            var id = $(this).data('payroll-id');
            Swal.fire({
                title: "@lang('messages.sweetAlertTitle')",
                text: "@lang('messages.recoverRecord')",
                icon: 'warning',
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: "@lang('messages.confirmDelete')",
                cancelButtonText: "@lang('app.cancel')",
                customClass: {
                    confirmButton: 'btn btn-primary mr-3',
                    cancelButton: 'btn btn-secondary'
                },
                showClass: {
                    popup: 'swal2-noanimation',
                    backdrop: 'swal2-noanimation'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ route('payroll.destroy', ':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        blockUI: true,
                        data: {
                            '_token': token,
                            '_method': 'DELETE'
                        },
                        success: function (response) {
                            if (response.status == "success") {
                                window.LaravelDataTables["payroll-table"].draw(false);
                            }
                        }
                    });
                }
            });
        });

        const applyQuickAction = () => {
            var url = "{{ route('payroll.get_status') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        };

        $('body').on('click', '#generate-payslip', function () {
            var month = $('#month').val();
            var year = $('#year').val();
            var cycle = $('#payrollCycle').val();
            var token = "{{ csrf_token() }}";

            var markLeavesPaid = '0';
            var markAbsentUnpaid = '0';
            var useAttendance = '0';
            var includeExpenseClaims = '0';
            var addTimelogs = '0';

            if ($('#mark_leaves_paid').is(':checked')) {
                markLeavesPaid = '1';
            }
            if ($('#mark_absent_unpaid').is(':checked')) {
                markAbsentUnpaid = '1';
            }
            if ($('#useAttendance').is(':checked')) {
                useAttendance = '1';
            }
            if ($('#includeExpenseClaims').is(':checked')) {
                includeExpenseClaims = '1';
            }
            if ($('#addTimelogs').is(':checked')) {
                addTimelogs = '1';
            }

            $.easyAjax({
                url: '{{route('payroll.generate_pay_slip')}}',
                container: '#genrate-payroll-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#generate-payslip",
                data: {
                    month: month,
                    year: year,
                    cycle: cycle,
                    markLeavesPaid: markLeavesPaid,
                    markAbsentUnpaid: markAbsentUnpaid,
                    useAttendance: useAttendance,
                    includeExpenseClaims: includeExpenseClaims,
                    addTimelogs: addTimelogs,
                    _token: token
                },
                success: function (response) {
                    if (response.status == "success") {
                        showTable();
                    }
                }
            })

        });

        const regeneratePayslip = () => {
            var month = $('#month').val();
            var year = $('#year').val();
            var token = "{{ csrf_token() }}";

            var markLeavesPaid = '0';
            var markAbsentUnpaid = '0';
            var useAttendance = '0';
            var includeExpenseClaims = '0';
            var addTimelogs = '0';

            if ($('#mark_leaves_paid').is(':checked')) {
                markLeavesPaid = '1';
            }
            if ($('#mark_absent_unpaid').is(':checked')) {
                markAbsentUnpaid = '1';
            }
            if ($('#useAttendance').is(':checked')) {
                useAttendance = '1';
            }
            if ($('#includeExpenseClaims').is(':checked')) {
                includeExpenseClaims = '1';
            }
            if ($('#addTimelogs').is(':checked')) {
                addTimelogs = '1';
            }

            var userIds = $("#payroll-table input:checkbox:checked").map(function () {
                return $(this).data('user-id');
            }).get();

            $.easyAjax({
                url: '{{route('payroll.generate_pay_slip')}}',
                container: '#genrate-payroll-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#generate-payslip",
                data: {
                    month: month,
                    year: year,
                    markLeavesPaid: markLeavesPaid,
                    markAbsentUnpaid: markAbsentUnpaid,
                    useAttendance: useAttendance,
                    includeExpenseClaims: includeExpenseClaims,
                    addTimelogs: addTimelogs,
                    userIds: userIds,
                    _token: token
                },
                success: function (response) {
                    if (response.status == "success") {
                        showTable();
                    }
                }
            });
        }

        $(document).on('click', '#update-status', function () {
            var salaryIds = $("#payroll-table input:checkbox:checked").map(function () {
                return $(this).val();
            }).get();
            let status = $("input[name='status']:checked").val();
            let month = $('#month :selected').text();
            let year = $('#year :selected').text();
            let paidOn = $("#paid_on").val();
            let category_id = $("#category_id").val();
            let expense_title = $("#expense_title").val();
            let paymentMethod = $("#salary_payment_method_id").val();
            let addExpenses = $("#add_expenses").val();
            let =
            $("#add_expenses").val();

            var token = "{{ csrf_token() }}";

            var url = "{{ route('payroll.update_status') }}";

            $.easyAjax({
                url: url,
                type: 'POST',
                container: '#change-status-form',
                disableButton: true,
                blockUI: true,
                buttonSelector: "#update-status",
                data: {
                    '_token': token,
                    salaryIds: salaryIds,
                    status: status,
                    paymentMethod: paymentMethod,
                    paidOn: paidOn,
                    add_expenses: addExpenses,
                    month: month,
                    year: year,
                    category_id: category_id,
                    expense_title: expense_title
                },
                success: function (response) {
                    if (response.status == "success") {
                        showTable();
                        $(MODAL_LG).modal('hide');
                    }
                }
            });
        });

        $(document).ready(function () {
            getCycleData();
        });

        $('body').on('change', '#payrollCycle', function () {
            var payroll = $(this).val()
            if (payroll == 1) {
                $('#select-label').html("@lang('app.select') @lang('app.month')");
            } else if (payroll == 4) {
                $('#select-label').html("@lang('payroll::app.selectRange')");
            } else {
                $('#select-label').html("@lang('payroll::app.selectWeek')");
            }
            getCycleData();
        });
        $('body').on('change', '#year', function () {
            getCycleData();
        });

        function getCycleData() {
            var payrollCycle = $('#payrollCycle').val();
            var year = $('#year').val();
            var token = "{{ csrf_token() }}";
            $.easyAjax({
                url: '{{route("payroll.get-cycle-data")}}',
                type: "POST",
                data: {
                    payrollCycle: payrollCycle,
                    year: year,
                    with_view: 'yes',
                    _token: token
                },
                success: function (response) {
                    $.unblockUI();
                    $('#month').html(response.view);
                    $('#month').selectpicker("refresh");
                    showTable();

                }
            })
        }

    </script>
    <script>
    $('#export-all').click(function () {
        var month = $('#month').val();
        var dates = month.split(' '); 
        var startDate = dates[0];
        var endDate = dates[1];
        var url = "{{ route('payroll.export', ['start_date' => ':start_date', 'end_date' => ':end_date']) }}";
        url = url.replace(':start_date', encodeURIComponent(startDate));
        url = url.replace(':end_date', encodeURIComponent(endDate));
        window.location.href = url;
    });
</script>
   <script>
    $('#export-all-details').click(function () {
        var month = $('#month').val();
        var dates = month.split(' '); 
        var startDate = dates[0];
        var endDate = dates[1];
        var url = "{{ route('payroll.export-details', ['start_date' => ':start_date', 'end_date' => ':end_date']) }}";
        url = url.replace(':start_date', encodeURIComponent(startDate));
        url = url.replace(':end_date', encodeURIComponent(endDate));
        window.location.href = url;
    });
</script>
@endpush
