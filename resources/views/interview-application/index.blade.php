@extends('layouts.app')

@push('datatable-styles')
@include('sections.datatable_css')
@endpush
@section('content')
<div class="content-wrapper"> 
    <button class="btn btn-sm btn-danger"  id="copyButton" onclick="copyLink({{ user()->company_id }})">Copy Form Link</button>
    <div class="d-flex flex-column w-tables rounded mt-3 bg-white">
    {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}
    </div>
</div>
@endsection

@push('scripts')
@include('sections.datatable_js')

<script>
    $('body').on('change', '.assign_role', function() {
        var userId = $(this).data('user-id');
        var assign_id = $(this).val();

        let url = "{{ route('assign.assignInterview', ':userId') }}";
        url = url.replace(':userId', userId);
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        console.log(url);

        $.ajax({
            url: url
            , method: 'post'
            , data: {
                assign_id: assign_id
                , _token: csrfToken
            }
            , success: function(response) {
                console.log(response);
                if (response.status == "success") {
                    Toastify({
    text: response.message,
    color:"green",
}).showToast();

                    window.location.reload();
                } else {
                    window.location.href = response.redirectUrl;
                }
            }
        });
    });

</script>
<script>
    $('body').on('change', '.status_update', function() {
        var userId = $(this).data('user-id');
        var status = $(this).val();
        console.log(status);
        let url = "{{ route('status.statusUpdate', ':userId') }}";
        url = url.replace(':userId', userId);
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: url
            , type: "POST"
            , BlockUI: true
            , container: '#Asset-table'
            , data: {
                status: status
                , _token: csrfToken
            }
            , success: function(response) {
                if (response.status === 'success') {
                // Example Toastify configuration
Toastify({
    text: "This is a toast message",
    duration: 5000, 
    gravity: "top", 
    position: "center", 
    backgroundColor: "#fff", 
    stopOnFocus: true, 
    close: true 
}).showToast();
                    window.location.href = response.redirectUrl;
                }
            }
        });
    });

</script>
<script>
    function copyLink(id) {
        var id = id;
        var baseUrl = window.location.protocol + "//" + window.location.host;
        var linkInput = document.createElement('input');
        linkInput.value = baseUrl + "/apply/" + id;
        document.body.appendChild(linkInput);
        linkInput.select();
        document.execCommand('copy');
        document.body.removeChild(linkInput);

        // Change button text to "Copied"
        var button = document.getElementById('copyButton'); 
        var originalButtonText = button.innerText;
        button.innerText = 'Copied';

        // Restore button text after 5 seconds
        setTimeout(function() {
            button.innerText = originalButtonText;
        }, 5000);
    }
</script>



@endpush
