@extends('layouts.app')

@push('styles')

    @include('sections.datatable_css')
    <style>
        .value-list li {
            list-style: disc;
        }
    </style>

@endpush

@section('content')

    <!-- SETTINGS START -->
    <div class="w-100 d-flex">

        @include('sections.setting-sidebar')

        <x-setting-card>

            <x-slot name="buttons">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <x-forms.button-primary icon="plus" id="add-field"
                                                class="mb-2">Assign Branch
                        </x-forms.button-primary>
                    </div>
                </div>
            </x-slot>

            <x-slot name="header">
                <div class="s-b-n-header" id="tabs">
                    <h2 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                        @lang($pageTitle)</h2>
                </div>
            </x-slot>

            <!-- LEAVE SETTING START -->
            <div class="col-lg-12 col-md-12 ntfcn-tab-content-left">
                <x-table class="table w-100 table-sm-responsive">
                    <x-slot name="thead">
                        <th>#</th>
                        <th>Branch Name</th>
                        <th>Employee</th>
                    </x-slot>
                </x-table>
            </div>
            <!-- LEAVE SETTING END -->

        </x-setting-card>

    </div>
    <!-- SETTINGS END -->

@endsection

@push('scripts')

    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>

    <script>

        $(function () {

            const table = $('#example').dataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: '{!! route('assign-branch.index') !!}',
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
                    {data: 's_no', name: 's_no', orderable: false, searchable: false, visible: true},
                    {data: 'branch_name', name: 'branch_name', orderable: true, searchable: true},
                    {data: 'employees', 
                    name: 'Employees',
                    orderable: false, 
                     searchable: false
                    },

                ]
            });

            $('body').on('click', '.sa-params', function () {
                const id = $(this).data('user-id');
                alert(id);

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

                        let url = "{{ route('assign-branch.destroy',':id') }}";
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
                                    table._fnDraw();
                                }
                            }
                        });
                    }
                });
            });

        });

        $('body').on('click', '#add-field', function () {
            const url = "{{ route('assign-branch.create')}}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

        $('body').on('click', '.list-custom-field', function () {
            const id = $(this).data('user-id');
            let url = "{{ route('assign-branch-master.list',':id') }}";
            url = url.replace(':id', id);
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        });

    </script>
@endpush
