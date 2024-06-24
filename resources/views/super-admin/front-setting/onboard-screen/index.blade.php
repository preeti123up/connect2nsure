
@extends('layouts.app')
@section('content')
    <!-- SETTINGS START -->
    <div class="w-100 d-flex">

        <x-super-admin.front-setting-sidebar :activeMenu="$activeSettingMenu" />
        <x-setting-card>
        <x-slot name="header">

        </x-slot>


        <x-slot name="buttons">
                <div class="row">

                    <div class="col-md-12 mb-2">
                        <x-forms.button-primary icon="plus" class="image-btn mb-2 addOnboardScreen" data-type="image">
                            @lang('Add')
                        </x-forms.button-primary>

                      
                    </div>

                </div>
        </x-slot>
        <div class="table-responsive" id="table-view">
                @include($view)
            </div>
      </x-setting-card>

    </div>
    
    <!-- SETTINGS END -->
@endsection

@push('scripts')
<script>

$('body').on('click', '.addOnboardScreen', function () {
        var settingId = $(this).data('id');
        var onboard = settingId == undefined ? '' : settingId;
        var type = $(this).data('type');
        var url = "{{ route('superadmin.front-settings.onboard-screen.create') }}?onboard-screenId="+onboard + "&type="+type;
        $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
        $.ajaxModal(MODAL_LG, url);
    });


    /* open add footer modal */
    $('body').on('click', '.edit-onboard-screen', function () {
        var id = $(this).data('id');
        var type = $(this).data('type');
        var url = "{{ route('superadmin.front-settings.onboard-screen.edit', ':id')}}?type="+type;
        url = url.replace(':id', id);
        $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
        $.ajaxModal(MODAL_LG, url);
    });
    


    $('body').on('click', '.read-more-table-row', function() {
        var id = $(this).data('id');
        var type = $(this).data('type');
        var settingId = $(this).data('setting-id');
        var onboard = settingId == undefined ? '' : settingId;

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
                var url = "{{ route('superadmin.front-settings.onboard-screen.delete', ':id') }}";
                url = url.replace(':id', id);

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    blockUI: true,
                    data: {
                        'type': type,
                        'onboard-screenId':onboard,
                        '_token': token,
                        '_method': 'DELETE'
                    },
                    success: function(response) {
                        if (response.status == "success") {
                            $('#table-view').html(response.html);
                            window.location.href = "{{ route('superadmin.front-settings.onboard-screen.readMore-details') }}";

                            // $('.row'+id).fadeOut();
                        }
                    }
                });
            }
        });
    });
    
    </script>

@endpush

