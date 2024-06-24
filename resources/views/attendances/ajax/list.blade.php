@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<link rel="stylesheet" href="{{ asset('vendor/css/daterangepicker.css') }}">
@endpush

@section('filter-section')
<div id="attendance-detail-section">
    <div class="row">
        <div class="col-sm-6 p-4">
            <div class="card bg-white border-0 b-shadow-4">
                <div class="card-body">
                    <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                        <p class="mb-0 text-lightest f-14 w-30 d-inline-block text-capitalize">@lang('modules.pendingAttendance.name')</p>
                        <p class="mb-0 text-dark-grey f-14">{{ ucwords($pendingAttendance->user->name) }}</p>
                    </div>
                    @if(isset($pendingAttendance->clock_in_outside_reason))
                    <x-cards.data-row :label="__('modules.pendingAttendance.outside_reason')" :value="ucwords($pendingAttendance->clock_in_outside_reason)" html="true" />
                    @if ($pendingAttendance->clock_in_latitude != '' && $pendingAttendance->clock_in_longitude != '')
                    IN {{ $pendingAttendance->clock_in_time->timezone(company()->timezone)->translatedFormat(company()->date_format . ' ' . company()->time_format) }}
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $pendingAttendance->clock_in_latitude }}%2C{{ $pendingAttendance->clock_in_longitude }}" target="_blank">
                        <i class="fa fa-map-marked-alt ml-2"></i> @lang('modules.attendance.showOnMap')</a>
                    @endif
                    @endif
                    @if(isset($pendingAttendance->clock_out_outside_reason))
                    <x-cards.data-row :label="__('modules.pendingAttendance.outside_reason')" :value="$pendingAttendance->clock_out_outside_reason" html="true" />
                    @if ($pendingAttendance->clock_out_latitude != '' && $pendingAttendance->clock_out_longitude != '')
                    Out {{ $pendingAttendance->clock_out_time ? $pendingAttendance->clock_out_time->timezone(company()->timezone)->translatedFormat(company()->date_format . ' ' . company()->time_format) : "N/A" }}
                    <a href="https://www.google.com/maps/search/?api=1&query={{ $pendingAttendance->clock_out_latitude }}%2C{{ $pendingAttendance->clock_out_longitude }}" target="_blank">
                        <i class="fa fa-map-marked-alt ml-2"></i> @lang('modules.attendance.showOnMap')</a>
                    @endif
                    @endif
                    <x-cards.data-row :label="__('modules.pendingAttendance.status')" :value="ucwords($pendingAttendance->status)" html="true" />
                </div>
                <div class="d-flex justify-content-center mb-4 mr-3">
                    @if($pendingAttendance->status=='approved')
                    <button class="btn btn-primary ml-2 approved-or-reject" data-action="rejected">Reject</button>
                    @endif
                    @if($pendingAttendance->status=='rejected')
                    <button class="btn btn-primary approved-or-reject" data-action="approved">Approve</button>
                    @endif
                    @if($pendingAttendance->status=='pending')
                    <button class="btn btn-primary approved-or-reject" data-action="approved">Approve</button>
                    <button class="btn btn-primary ml-2 approved-or-reject" data-action="rejected">Reject</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    $(document).ready(function () {
        $('.approved-or-reject').on('click', function () {
            var action = $(this).data('action');
            $.ajax({
                url: '{{ route('attendances.approve-or-reject', ['id' => $pendingAttendance->id]) }}',
                type: 'POST',
                data: {
                    action: action
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    toastr.success('Attendance Updated successfully!');
                    location.reload();
                },
                error: function (xhr, status, error) {
                    toastr.error('An error occurred while updating attendance.');
                }
            });
        });
    });
</script>
@endpush
