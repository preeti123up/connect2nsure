@extends('layouts.app')
@push('datatable-styles')
@include('sections.datatable_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endpush
@section('content')
<x-filters.filter-box>
        <!-- CLIENT START -->
        <div class="select-box py-2 d-flex pr-2 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">Asset Type</p>
            <div class="select-status">
                <select class="form-control select-picker" name="type" id="type" data-live-search="true"
                        data-size="8">
                        <option value="all">@lang('app.all')</option>

                    @foreach ($assetDevice as $assetDevice)
                    <option value="{{ $assetDevice->id }}">{{ $assetDevice->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">@lang('app.employee')</p>
            <div class="select-status">
                <select class="form-control select-picker" name="employee" id="employee" data-live-search="true"
                        data-size="8">
                    @if ($employees->count() > 1 || in_array('admin', user_roles()))
                        <option value="all">@lang('app.all')</option>
                    @endif
                    @foreach ($employees as $employee)
                        <x-user-option :user="$employee"/>
                    @endforeach
                </select>
            </div>
        </div>
         @if (in_array('admin', user_roles()))
                <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-2 f-14 text-dark-grey d-flex align-items-center">Status</p>
            <div class="select-status">
                <select class="form-control select-picker" name="status" id="status">
                    <option value="all">@lang('app.all')</option>
                        <option value="Available">Available</option>
                        <option value="Lost">Lost</option>
                        <option value="Damaged">Damaged</option>
                        <option value="Under Maintenance">Under Maintenance</option>
                        <option value="Non Functional">Non Functional</option>
                        <option value="Lended">Lended</option>
                        <option value="Returned">Returned</option>
                </select>
            </div>
        </div>     
                    @endif
        
        <!-- RESET START -->
        <div class="select-box d-flex py-1 px-lg-2 px-md-2 px-0">
            <x-forms.button-secondary class="btn-xs d-none" id="reset-filters" icon="times-circle">
                @lang('app.clearFilters')
            </x-forms.button-secondary>
        </div>
        <!-- RESET END -->
    </x-filters.filter-box>

    <div class="content-wrapper">
     
        @php($addAssetsPermission = user()->permission('add_assets'))
              @if($addAssetsPermission  == 'all' || in_array('admin', user_roles()) || $addAssetsPermission  == 'owned')
        <div class="d-grid d-lg-flex d-md-flex action-bar">
                    <x-forms.link-primary :link="route('asset.create')" class="mr-3 openRightModal float-left"
                                          icon="plus">
                        @lang('add Asset')
                    </x-forms.link-primary>
        </div>
        @endif
        
   
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}
        </div>
    </div>


@endsection
@push('scripts')
@include('sections.datatable_js')

<script>

$('#assets-table').on('preXhr.dt', function (e, settings, data) {
            const status = $('#status').val();
            const employee = $('#employee').val();
            const type = $('#type').val();
     
            data['status'] = status;
            data['employee'] = employee;
            data['type'] = type;
        });

        const showTable = () => {
            window.LaravelDataTables["assets-table"].draw(false);
        }

        $('#employee, #status, #type').on('change keyup',function () {
                if ($('#status').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#employee').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                } else if ($('#type').val() != "all") {
                    $('#reset-filters').removeClass('d-none');
                }
                else {
                    $('#reset-filters').addClass('d-none');
                }
                showTable();
            });

            $('#reset-filters').click(function () {
            $('#filter-form')[0].reset();
            $('.filter-box .select-picker').selectpicker("refresh");
            $('#reset-filters').addClass('d-none');
            showTable();
        });

 $('body').on('click', '.delete-table-row', function () {
            var id = $(this).data('asset-id');
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
                    var url = "{{ route('asset.destroy', ':id') }}";
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
                                window.location.href = response.redirectUrl;

                            }
                        }
                    });
                }
            });
        });
    </script>
  <script>
    $('body').on('click', '.asset-action-approved,.asset-action-rejected', function () {
      var assetId = $(this).data('asset-id');
      var action=$(this).data('asset-action');
  
      $.ajax({
          url: '{{ route('asset.status','') }}/'+assetId,
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: {
          action: action
      },
          success: function (response) {
              toastr.success('Status Updated Successfully!');
              window.location.reload(true);
          },
          error: function (xhr, status, error) {
              console.error(error);
          }
      });
  });
    




       

</script>

<script>
    lightbox.option({
        'resizeDuration': 500,
        'wrapAround': true
    })
    
</script>

@endpush
