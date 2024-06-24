@extends('layouts.app')
@push('datatable-styles')
@include('sections.datatable_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<style>
    #toast-container > .toast-success {
    background-color: #5cb85c; /* Background color for success notifications */
    color: #fff; /* Text color for success notifications */
}

#toast-container > .toast-error {
    background-color: #d9534f; /* Background color for error notifications */
    color: #fff; /* Text color for error notifications */
}
</style>


@endpush
@section('content')
    <div class="content-wrapper">
        <div class="d-grid d-lg-flex d-md-flex action-bar">
                    <x-forms.link-primary :link="route('reimbursement.create')" class="mr-3 openRightModal float-left"
                                          icon="plus">
                        @lang('add Reimbursement')
                    </x-forms.link-primary>
        </div>
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}
        </div>
    </div>
@endsection
@push('scripts')
@include('sections.datatable_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
 $('body').on('click', '.delete-table-row', function () {
            var id = $(this).data('reimbursement-id');
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
                    var url = "{{ route('reimbursement.destroy', ':id') }}";
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
                                showTable();

                            }
                        }
                    });
                }
            });
        });
    </script>
  <script>
       $('body').on('click', '.reimbursement-action-approved,.reimbursement-action-rejected,.already_paid-action-approved', function () {
      var reimbursementId = $(this).data('reimbursement-id');
      var action=$(this).data('reimbursement-action');
      $.ajax({
          url: '{{ route('reimbursement.status','') }}/'+reimbursementId,
          type: 'POST',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: {
          action: action
      },
          success: function (response) {
              toastr.success('Status Updated Successfully!');
              location.reload();

  
          },
          error: function (xhr, status, error) {
              console.error(error);
          }
      });
  });
    
</script>

@endpush
