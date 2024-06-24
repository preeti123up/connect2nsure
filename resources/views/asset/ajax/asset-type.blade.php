<style>
.body{
    text-align:right;
}
.dataTables_length{
    display:none;
}
.dataTables_paginate{
    padding-left:221px;
}
th[aria-label="Action"] {
    width: 200px !important;
    padding-right: 44px !important;
}
</style>
<div class="modal-header">
    <h5 class="modal-title">@lang('Add Asset Type')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
<div class="portlet-body">

<!-- LEAVE SETTING START -->
            <div class="col-lg-12 col-md-12">
                <x-table class="table w-100 table-sm-responsive" style="align-right">
                    <x-slot name="thead">
                        <th>#</th>
                        <th>Name </th>
                        <th class="text-right">@lang('app.action')</th>
                    </x-slot>
                </x-table>
            </div>
            <!-- LEAVE SETTING END -->
          <x-form id="createForm" method="POST" class="ajax-form">
            <div class="row">
             <div class="col-lg-12">
             <x-forms.text :fieldLabel="__('Name')" fieldName="name" fieldId="name"  fieldRequired="true"  />
            </div>
            </div>
               
        </x-form>
    </div>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="save-custom-field" icon="check">@lang('app.save')</x-forms.button-primary>
</div>

    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    <script src="{{ asset('vendor/jquery/bootstrap-colorpicker.js') }}"></script>
    <script src="{{ asset('vendor/jquery/image-picker.min.js') }}"></script>

  
    <script>
          $(function(){
               const table = $('#example').dataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: '{!! route('devices.index') !!}',
                order: [[0, "desc"]],
                deferRender: true,
                language: {
                    "url": "{{__("app.datatable") }}"
                },
                "fnDrawCallback": function (oSettings) {
                    $("body").tooltip({
                        selector: '[data-toggle="tooltip"]'
                    });
                },
                columns: [
                    {data: 'id', name: 'id', orderable: false, searchable: false, visible: true},
                    {data: 'name', name: 'Name', orderable: false, searchable: false},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: "text-right"
                    }
                ]
            });
        });
        </script>

<script>
    $(".select-picker").selectpicker();


    $('#save-custom-field').click(function () {
        $.easyAjax({
            url: "{{route('devices.store')}}",
            container: '#createForm',
            type: "POST",
            data: $('#createForm').serialize(),
            file: true,
            blockUI: true,
            buttonSelector: "#save-custom-field",
            success: function (response) {
                if (response.status === 'success') {
                    window.location.reload();
                }
            }
        })
        return false;
    })
    $('body').on('click', '.delete-field', function () {
                const id = $(this).data('device-id');
                Swal.fire({
                    title: "@lang('messages.sweetAlertTitle')",
                    text: "@lang('messages.deleteField')",
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

                        let url = "{{ route('devices.destroy',':id') }}";
                        url = url.replace(':id', id);

                        const token = "{{ csrf_token() }}";

                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            blockUI: true,
                            data: {'_token': token, '_method': 'DELETE'},
                            success: function (response) {
                                if (response.status == "success") {
                                    $.unblockUI();
                                    window.location.reload();
                                }
                            }
                        });
                    }
                });
            });
</script>

