@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatable_css')
    <style>
        .filter-box {
            z-index: 2;
        }
    </style>
@endpush



@section('content')
    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">

       

        <!-- leave table Box Start -->
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white">

            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}

        </div>
        <!-- leave table End -->

    </div>
    <!-- CONTENT WRAPPER END -->

@endsection

@push('scripts')
    @include('sections.datatable_js')
    <script>
        const showTable = () => {
            window.LaravelDataTables["pendingAttendance-table"].draw(false);
        }
    </script>
    <script>
      $('body').on('click', '.attendance-action-approved', function () {
        var attendanceId = $(this).data('attendance-id');
        var action=$(this).data('attendance-action');
    
        $.ajax({
            url: '{{ route('attendances.approve-or-reject','') }}/'+attendanceId,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
            action: action
        },
            success: function (response) {
                // Handle success with Toastr
                location.reload();
            },
            error: function (xhr, status, error) {
                // Handle errors, for example, show an error message
                console.error(error);
            }
        });
    });
      
</script>
<script>
     $('body').on('click', '.attendance-action-rejected', function () {
        var attendanceId = $(this).data('attendance-id');
        var action=$(this).data('attendance-action');
        $.ajax({
            url: '{{ route('attendances.approve-or-reject','') }}/'+attendanceId,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
            action: action
        },
            success: function (response) {
                // Handle success with Toastr
                location.reload();
            },
            error: function (xhr, status, error) {
                // Handle errors, for example, show an error message
                console.error(error);
            }
        });
    });
</script>

@endpush
