@extends('layouts.app')

@push('styles')
    @include('sections.datatable_css')
    <style>
        .value-list li {
            list-style: disc;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('vendor/css/bootstrap-colorpicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/css/image-picker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/css/dropzone.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

@endpush

@section('content')
    <!-- SETTINGS START -->
    <div class="w-100 d-flex">

        @include('sections.setting-sidebar')

        <x-setting-card>

            <x-slot name="buttons">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <x-forms.link-primary :link="route('wishes.create')" id="wishes" class="mr-3 openRightModal float-left mb-1 mb-lg-0 mb-md-0"
                            icon="plus">
                            @lang('modules.wishes.addNewWishes')
                        </x-forms.link-primary>
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
                        <th>@lang('modules.wishes.wishesType') </th>
                        <th>Web Image</th>
                        <th>App Image </th>
                        <th>@lang('modules.wishes.wishesFontColor') </th>
                        <th>@lang('modules.wishes.wishesMessage') </th>
                        <th class="text-right">@lang('app.action')</th>
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
    <script src="{{ asset('vendor/jquery/bootstrap-colorpicker.js') }}"></script>
    <script src="{{ asset('vendor/jquery/image-picker.min.js') }}"></script>

    <script>
        $(document).ready(function(){
            Dropzone.autoDiscover = false;
            const uploadFile = "{{ route('update-settings.store') }}?_token={{ csrf_token() }}";
            const myDrop = new Dropzone("#file-upload-dropzone", {
                url: uploadFile,
                acceptedFiles: 'image/*',
                addRemoveLinks: true,
                dictDefaultMessage: "@lang('app.dropFileToUpload')",
            });
            myDrop.on("complete", function(file) {
                if (myDrop.getRejectedFiles().length == 0) {
                    window.location.reload();
                }
            });
        
    
          
        });
    </script>
    <script>
          $(function(){
               const table = $('#example').dataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: '{!! route('wishes.index') !!}',
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
                    {data: 'WishesType', name: 'WishesType', orderable: false, searchable: false},
                    {data: 'Image', name: 'Image', orderable: true, searchable: true},
                    {data: 'AppImage', name: 'AppImage', orderable: true, searchable: true},
                    {data: 'font_color', name: 'font_color', orderable: true, searchable: true},
                    {data: 'message', name: 'message', orderable: true, searchable: true},
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

        $('body').on('click', '.sa-params', function () {
                const id = $(this).data('user-id');
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

                        let url = "{{ route('wishes.destroy',':id') }}";
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
                                    window.location.href='{{route('wishes.index')}}';

                                    
                                }
                            }
                        });
                    }
                });
            });
    </script>
@endpush
