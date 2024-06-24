<div class="modal-header">
    <h5 class="modal-title">Assign Employee List</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
    <div class="portlet-body">
    <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Action</th>
                    <!-- Add more table headers as needed -->
                </tr>
            </thead>
            <tbody>
                @foreach($branches as $index=>$branch)
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $branch->name }}</td>
                        <td>
                            <input type="checkbox" class="delete-checkbox" data-user-id="{{ $branch->id }}">
                        </td>
                    </td>
                        <!-- Add more table cells as needed -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="update-custom-field" icon="check">Delete</x-forms.button-primary>
</div>

<script>
$('body').on('click', '#update-custom-field', function () {
    const selectedIds = $('.delete-checkbox:checked').map(function() {
        return $(this).data('user-id');
    }).get();

    if (selectedIds.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'No selection',
            text: 'Please select at least one item to delete',
        });
        return;
    }

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
            let url = "{{ route('assign-branch.destroy', ':id') }}";
            url = url.replace(':id', selectedIds.join(','));

            const token = "{{ csrf_token() }}";

            $.easyAjax({
                type: 'POST',
                url: url,
                blockUI: true,
                data: { '_token': token, '_method': 'DELETE' },
                success: function (response) {
                    if (response.status == "success") {
                        $.unblockUI();
                        // Redraw the DataTable if you're using DataTables
                        // For example, assuming you have a DataTable called 'table'
                       location.reload();
                    }
                }
            });
        }
    });
});


</script>

