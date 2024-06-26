<style>
    .task_view{
        border: 0px !important;
    }
    .action-hover:hover{
        background-color: #ffffff !important;
    }
</style>
<div class="modal-header">
    <h5 class="modal-title">@lang('app.total') @lang('app.leave') ( {{$multipleLeaves[0]->user->name}} )</h5>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="col-lg-12 col-md-12 ntfcn-tab-content-left w-100 p-0">
   @include('leaves.multiple-leave-table')
</div>
<div class="modal-footer">
        @php
          use App\Models\LeaveSetting;
         $reportingPermission = LeaveSetting::value('manager_permission');
            if($pendingCountLeave != count($multipleLeaves)){
                $approveTitle = __('modules.leaves.approveRemaining');
                $rejectTitle = __('modules.leaves.rejectRemaining');
              
            }
            else {
                $approveTitle = __('modules.leaves.approveAll');
                $rejectTitle = __('modules.leaves.rejectAll');
            }
        @endphp

        @if (($pendingCountLeave > 0 && $approveRejectPermission === 'all') || ($reportingPermission ==='approved'  && $pendingCountLeave > 0) && auth()->id() !== $multipleLeaves[0]->user->id)
            <a class="btn btn-secondary rounded f-14 p-2 leave-action-approved" data-leave-id="{{ $multipleLeaves->first()->unique_id }}"
                data-leave-action="approved" data-type="approveAll" class="mr-3" icon="check" href="javascript:;">
                <i class="fa fa-check mr-2"></i>{{$approveTitle}}</a>

            <a class="btn btn-secondary rounded f-14 p-2 leave-action-reject" data-leave-id="{{ $multipleLeaves->first()->unique_id }}"
                data-leave-action="rejected" data-type="rejectAll" class="mr-3" icon="check" href="javascript:;">
                <i class="fa fa-times mr-2"></i>{{$rejectTitle}}</a>
        @endif

    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
</div>
