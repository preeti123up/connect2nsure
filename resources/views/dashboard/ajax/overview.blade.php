@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

@endpush
<script src="{{ asset('vendor/jquery/frappe-charts.min.iife.js') }}"></script>

<div class="row">
    @if (in_array('clients', user_modules()) && in_array('total_clients', $activeWidgets))
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <a href="{{ route('clients.index') }}">
                <x-cards.widget :title="__('modules.dashboard.totalClients')" :value="$counts->totalClients"
                    icon="users">
                </x-cards.widget>
            </a>
        </div>
    @endif

    @if (in_array('employees', user_modules()) && in_array('total_employees', $activeWidgets))
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <a href="{{ route('employees.index') }}">
                <x-cards.widget :title="__('modules.dashboard.totalEmployees')" :value="$counts->totalEmployees"
                    icon="user">
                </x-cards.widget>
            </a>
        </div>
    @endif

    @if (in_array('projects', user_modules()) && in_array('total_projects', $activeWidgets))
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <a href="{{ route('projects.index'). '?projects=all' }}">
                <x-cards.widget :title="__('modules.dashboard.totalProjects')" :value="$counts->totalProjects"
                    icon="layer-group">
                </x-cards.widget>
            </a>
        </div>
    @endif

    @if (in_array('invoices', user_modules()) && in_array('total_unpaid_invoices', $activeWidgets))
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <a href="{{ route('invoices.index') . '?status=pending' }}">
                <x-cards.widget :title="__('modules.dashboard.totalUnpaidInvoices')"
                    :value="$counts->totalUnpaidInvoices" icon="file-invoice">
                </x-cards.widget>
            </a>
        </div>
    @endif

    @if (in_array('timelogs', user_modules()) && in_array('total_hours_logged', $activeWidgets))
        <div class="col-xl-3 col-lg-6 col-md-6">
            <a href="{{ route('time-log-report.index') }}">
                <x-cards.widget :title="__('modules.dashboard.totalHoursLogged')" :value="$counts->totalHoursLogged"
                    icon="clock">
                </x-cards.widget>
            </a>
        </div>
    @endif

    @if (in_array('tasks', user_modules()) && in_array('total_pending_tasks', $activeWidgets))
        <div class="col-xl-3 col-lg-6 col-md-6">
            <a href="{{ route('tasks.index') }}?status=pending_task&type=public">
                <x-cards.widget :title="__('modules.dashboard.totalPendingTasks')" :value="$counts->totalPendingTasks"
                    icon="tasks" :info="__('modules.dashboard.privateTaskInfo')">
                </x-cards.widget>
            </a>
        </div>
    @endif

    @if (in_array('attendance', user_modules()) && in_array('total_today_attendance', $activeWidgets))
        <div class="col-xl-3 col-lg-6 col-md-6">
            <a href="{{ route('attendances.index') }}">
                <x-cards.widget :title="__('modules.dashboard.totalTodayAttendance')"
                    :value="$counts->totalTodayAttendance.'/'.$counts->totalEmployees" icon="calendar-check">
                </x-cards.widget>
            </a>
        </div>
    @endif

    @if (in_array('tickets', user_modules()) && in_array('total_unresolved_tickets', $activeWidgets))
        <div class="col-xl-3 col-lg-6 col-md-6">
            <a href="{{ route('tickets.index') . '?status=open' }}">
                <x-cards.widget :title="__('modules.tickets.totalUnresolvedTickets')"
                    :value="floor($counts->totalOpenTickets)" icon="ticket-alt">
                </x-cards.widget>
            </a>
        </div>
    @endif

</div>

<div class="row">
               @if(collect(user()->roles)->pluck('name')->contains('admin'))
  @if($pendingAttendance->count())
     <div class="col-sm-12 col-lg-6 mt-3">
         <x-cards.data :title="__('modules.pendingAttendance.pending').' <i class=\'fa fa-question-circle\' data-toggle=\'popover\' data-placement=\'top\' data-content=\''.__('app.from').' '.$startDate->translatedFormat(company()->date_format).' '.__('app.to').' '.$endDate->translatedFormat(company()->date_format).'\' data-trigger=\'hover\'></i>'" padding="false" otherClasses="h-200">
             <x-table>
                 @forelse ($pendingAttendance as $item)
                     <tr>
                         <td class="pl-20">
                            {{ucwords($item->user->name)}}
                         </td>
                        <td class="f-14">
                             @if($item->clock_in_outside_reason)
                             In: {{ucwords($item->clock_in_outside_reason)}}
                             @else
                             N/A
                             @endif
                              @if($item->clock_in_latitude && $item->clock_in_longitude)
                              <a href="https://www.google.com/maps/search/?api=1&query={{$item->clock_in_latitude }}%2C{{ $item->clock_in_longitude  }}" target="_blank">
                                 <i class="fa fa-map-marked-alt ml-2"></i>In</a>
                             @endif
                         </td>   
                         <td class="f-14">
                            @if($item->clock_out_outside_reason)
                             Out: {{ucwords($item->clock_out_outside_reason)}}
                             @else
                             N/A
                             @endif
                              @if($item->clock_out_latitude && $item->clock_out_longitude)
                                  <a href="https://www.google.com/maps/search/?api=1&query={{$item->clock_out_latitude }}%2C{{ $item->clock_out_longitude  }}" target="_blank">
                                 <i class="fa fa-map-marked-alt ml-2"></i> Out</a>
                            @endif
                        </td> 
                         <td class="text-darkest-grey">{{ $item->created_at->translatedFormat(company()->date_format) }}</td>
                         <td> 
                     </td>
                         <td align="right" class="pr-20">
                             <div class="task_view">
                                 <a href="{{ route('attendances.list', [$item->id]) }}"
                                     class="taskView ">@lang('app.view')</a>
                                 <div class="dropdown">
                                     <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle"
                                         type="link" id="dropdownMenuLink" data-toggle="dropdown"
                                         aria-haspopup="true" aria-expanded="false">
                                         <i class="icon-options-vertical icons"></i>
                                     </a> 
                                     <!-- Dropdown - User Information -->
                                     @if(!($item->ood_status=="approved"))
                                      <div class="dropdown-menu dropdown-menu-right"
                                         aria-labelledby="dropdownMenuLink" tabindex="0">
                                         <a class="dropdown-item attendance-action-approved" data-attendance-id='{{ $item->id }}'
                                             data-attendance-action="approved" href="javascript:;" >
                                             <i class="fa fa-check mr-2"></i>
                                             {{ __('app.approve') }}
                                         </a>
                                         <a data-attendance-id='{{ $item->id }}' data-attendance-action="rejected"
                                             class="dropdown-item  attendance-action-rejected" href="javascript:;">
                                             <i class="fa fa-times mr-2"></i>
                                             {{ __('app.reject') }}
                                         </a>
                                        
                                     </div> 
                                     @endif
                                  </div>
                             </div>
                         </td> 
                     </tr>
                 @empty
                     <tr>
                         <td colspan="5" class="shadow-none">
                             <x-cards.no-record icon="calendar" :message="__('messages.noRecordFound')"></x-cards.no-record>
                         </td>
                     </tr>
                 @endforelse
             </x-table>
         </x-cards.data>
     </div>
     @endif
  @endif
        <!-- assets start -->
     @if(collect(user()->roles)->pluck('name')->contains('admin'))
        <div class="col-sm-12 col-lg-6 mt-3">
            <x-cards.data :title="__('Pending Assets').' <i class=\'fa fa-question-circle\' data-toggle=\'popover\' data-placement=\'top\' data-content=\''.__('app.from').' '.$startDate->translatedFormat(company()->date_format).' '.__('app.to').' '.$endDate->translatedFormat(company()->date_format).'\' data-trigger=\'hover\'></i>'" padding="false" otherClasses="h-200">
                <x-table>
                    @forelse ($pendingAssets as $item)
                        <tr>
                            <td style="padding-left:0;">
                                <div class="card-horizontal align-items-center mt-0">
                                    <div class="card-img">
                                        <img class="" src="{{ $item->lendedAsset->last()->user->image_url }}" alt="Card image">
                                    </div>
                                    <div class="card-body border-0 p-0">
                                        <h4 class="card-title text-dark f-13 f-w-500 mb-0">{{ $item->lendedAsset->last()->user->name }}</h4>
                                    </div>
                                </div>
                            </td>
                            <td class="f-14">
                                {{ucwords($item->status)}}
                            </td>
                            <td class="f-14">
                            {{$item->lendedAsset->last()->date_of_return ?\Carbon\Carbon::CreateFromTimestamp(strtotime($item->lendedAsset->last()->date_of_return))->format('d M Y'):'';}}
                           </td>
                            <td class="f-14">
                             
                            </td>
                            <td align="right" class="pr-20">
                                <div class="task_view">
                                    <a href="{{ route('asset.list', ['id' => $item->id])}}"
                                        class="taskView ">@lang('app.view')</a>
                                    <div class="dropdown">
                                        <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle"
                                            type="link" id="dropdownMenuLink" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-options-vertical icons"></i>
                                        </a> 
                                        <!-- Dropdown - User Information -->
                                         <div class="dropdown-menu dropdown-menu-right"
                                            aria-labelledby="dropdownMenuLink" tabindex="0">
                                            <a class="dropdown-item asset-action-approved" data-asset-id='{{ $item->lendedAsset->last()->id }}'
                                                data-asset-action="Approved" href="javascript:;" >
                                                <i class="fa fa-check mr-2"></i>
                                                {{ __('Approve') }}
                                            </a>
                                           <a data-asset-id='{{ $item->lendedAsset->last()->id }}' data-asset-action="Rejected"
                                                class="dropdown-item  asset-action-rejected" href="javascript:;">
                                                <i class="fa fa-times mr-2"></i>
                                                {{ __('app.reject') }}
                                            </a>
                                           
                                        </div> 
                              
                                     </div>
                                </div>
                            </td> 
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="shadow-none">
                                <x-cards.no-record icon="calendar" :message="__('messages.noRecordFound')"></x-cards.no-record>
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-cards.data>
        </div>
     @endif

     <!--end assets -->
     @if(count($reimbursement)>0)
       @if(collect(user()->roles)->pluck('name')->contains('admin'))
        <div class="col-sm-12 col-lg-6 mt-3">
            <x-cards.data :title="__('Pending Reimbursement').' <i class=\'fa fa-question-circle\' data-toggle=\'popover\' data-placement=\'top\' data-content=\''.__('app.from').' '.$startDate->translatedFormat(company()->date_format).' '.__('app.to').' '.$endDate->translatedFormat(company()->date_format).'\' data-trigger=\'hover\'></i>'" padding="false" otherClasses="h-200">
                <x-table>
                    @forelse ($reimbursement as $item)
                          
                        <tr>
                            <td class="pl-20">
                               {{ucwords($item->name)}}
                            </td>
                            <td class="text-darkest-grey">{{ $item->created_at->translatedFormat(company()->date_format) }}</td>
                            <td class="f-14">
                                {{ucwords($item->status)}}
                            </td>
                            <td class="f-14">
                                {{ucwords($item->uniqueIdCount)}}
                                </button>
                            </td>
                            <td class="f-14">
                                {{ucwords($item->totalAmount)}}
                                </button>
                            </td>
                            <td align="right" class="pr-20">
                                <div class="task_view">
                                    <a href="{{ route('reimbursement.list', ['id' => $item->id, 'uniqueId' => $item->uniqueId]) }}"
                                        class="taskView ">@lang('app.view')</a>
                                    <div class="dropdown">
                                        <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle"
                                            type="link" id="dropdownMenuLink" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-options-vertical icons"></i>
                                        </a> 
                                        <!-- Dropdown - User Information -->
                                        @if(!($item->status=="Approved"))
                                         <div class="dropdown-menu dropdown-menu-right"
                                            aria-labelledby="dropdownMenuLink" tabindex="0">
                                            <a class="dropdown-item reimbursement-action-approved" data-reimbursement-id='{{ $item->uniqueId }}'
                                                data-reimbursement-action="Approved" href="javascript:;" >
                                                <i class="fa fa-check mr-2"></i>
                                                {{ __('Approve All') }}
                                            </a>
                                            <a data-reimbursement-id='{{ $item->uniqueId }}' data-reimbursement-action="Rejected"
                                                class="dropdown-item  reimbursement-action-rejected" href="javascript:;">
                                                <i class="fa fa-times mr-2"></i>
                                                {{ __('app.reject') }}
                                            </a>
                                           
                                        </div> 
                                        @endif
                                     </div>
                                </div>
                            </td> 
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="shadow-none">
                                <x-cards.no-record icon="calendar" :message="__('messages.noRecordFound')"></x-cards.no-record>
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-cards.data>
        </div>
     @endif
     @endif
    @if (in_array('payments', user_modules()) && in_array('recent_earnings', $activeWidgets))
        <div class="col-sm-12 col-lg-6 mt-3">
            <x-cards.data :title="__('app.income').' <i class=\'fa fa-question-circle\' data-toggle=\'popover\' data-placement=\'top\' data-content=\''.__('app.from').' '.$startDate->translatedFormat(company()->date_format).' '.__('app.to').' '.$endDate->translatedFormat(company()->date_format).'\' data-trigger=\'hover\'></i>'">
                <x-bar-chart id="task-chart1" :chartData="$earningChartData" height="300"></x-bar-chart>
            </x-cards.data>
        </div>
    @endif

    @if (in_array('timelogs', user_modules()) && in_array('timelogs', $activeWidgets))
        <div class="col-sm-12 col-lg-6 mt-3">
            <x-cards.data :title="__('app.menu.timeLogs').' <i class=\'fa fa-question-circle\' data-toggle=\'popover\' data-placement=\'top\' data-content=\''.__('app.from').' '.$startDate->translatedFormat(company()->date_format).' '.__('app.to').' '.$endDate->translatedFormat(company()->date_format).'\' data-trigger=\'hover\'></i>'">
                <x-line-chart id="task-chart2" :chartData="$timlogChartData" height="300"></x-line-chart>
            </x-cards.data>
        </div>
    @endif

    @if (in_array('leaves', user_modules()) && in_array('settings_leaves', $activeWidgets))
        <div class="col-sm-12 col-lg-6 mt-3">
            <x-cards.data :title="__('modules.leaves.pendingLeaves').' <i class=\'fa fa-question-circle\' data-toggle=\'popover\' data-placement=\'top\' data-content=\''.__('app.from').' '.$startDate->translatedFormat(company()->date_format).' '.__('app.to').' '.$endDate->translatedFormat(company()->date_format).'\' data-trigger=\'hover\'></i>'" padding="false" otherClasses="h-200">
                <x-table>
                    @forelse ($leaves as $item)
                        <tr>
                            <td class="pl-20">
                                <x-employee :user="$item->user" />
                            </td>
                            <td class="text-darkest-grey">{{ $item->leave_date->translatedFormat(company()->date_format) }}</td>
                            <td class="f-14">
                                <x-status :style="'color:'.$item->type->color" :value="$item->type->type_name"></x-status>
                            </td>
                            <td align="right" class="pr-20">
                                <div class="task_view">
                                    <a href="{{ route('leaves.show', [$item->id]) }}"
                                        class="taskView openRightModal">@lang('app.view')</a>
                                    <div class="dropdown">
                                        <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle"
                                            type="link" id="dropdownMenuLink" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-options-vertical icons"></i>
                                        </a>
                                        <!-- Dropdown - User Information -->
                                        <div class="dropdown-menu dropdown-menu-right"
                                            aria-labelledby="dropdownMenuLink" tabindex="0">
                                            <a class="dropdown-item leave-action-approved" data-leave-id='{{ $item->id }}'
                                                data-leave-action="approved" href="javascript:;">
                                                <i class="fa fa-check mr-2"></i>
                                                {{ __('app.approve') }}
                                            </a>
                                            <a data-leave-id='{{ $item->id }}' data-leave-action="rejected"
                                                class="dropdown-item leave-action-reject" href="javascript:;">
                                                <i class="fa fa-times mr-2"></i>
                                                {{ __('app.reject') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="shadow-none">
                                <x-cards.no-record icon="calendar" :message="__('messages.noRecordFound')"></x-cards.no-record>
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-cards.data>
        </div>
    @endif

    @if (in_array('tickets', user_modules()) && in_array('new_tickets', $activeWidgets))
        <div class="col-sm-12 col-lg-6 mt-3">
            <x-cards.data :title="__('modules.dashboard.openTickets').' <i class=\'fa fa-question-circle\' data-toggle=\'popover\' data-placement=\'top\' data-content=\''.__('app.from').' '.$startDate->translatedFormat(company()->date_format).' '.__('app.to').' '.$endDate->translatedFormat(company()->date_format).'\' data-trigger=\'hover\'></i>'" padding="false" otherClasses="h-200">
                <x-table>
                    @forelse ($newTickets as $item)
                        <tr>
                            <td class="pl-20">
                                <div class="avatar-img rounded">
                                    <img src="{{ $item->requester->image_url }}"
                                        alt="{{ $item->requester->name }}" title="{{ $item->requester->name }}">
                                </div>
                            </td>
                            <td><a href="{{ route('tickets.show', $item->ticket_number) }}"
                                    class="text-darkest-grey">{{ $item->subject }}</a>
                                <br />
                                <span class="f-10 text-lightest mt-1">{{ $item->requester->name }}</span>
                            </td>
                            <td class="text-darkest-grey" width="15%">{{ $item->updated_at->translatedFormat(company()->date_format) }}</td>
                            <td class="f-14 pr-20 text-right" width="20%">
                                @php
                                    if ($item->priority == 'low') {
                                        $priority = 'dark-green';
                                    } elseif ($item->priority == 'medium') {
                                        $priority = 'blue';
                                    } elseif ($item->priority == 'high') {
                                        $priority = 'yellow';
                                    } elseif ($item->priority == 'urgent') {
                                        $priority = 'red';
                                    }
                                @endphp
                                <x-status :color="$priority" :value="__('app.' . $item->priority)" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="shadow-none">
                                <x-cards.no-record icon="ticket-alt" :message="__('messages.noRecordFound')" />
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-cards.data>
        </div>
    @endif

    @if (in_array('tasks', user_modules()) && in_array('overdue_tasks', $activeWidgets))
        <div class="col-sm-12 col-lg-6 mt-3">
            <x-cards.data :title="__('modules.dashboard.totalPendingTasks').' <i class=\'fa fa-question-circle\' data-toggle=\'popover\' data-placement=\'top\' data-content=\''.__('app.from').' '.$startDate->translatedFormat(company()->date_format).' '.__('app.to').' '.$endDate->translatedFormat(company()->date_format).'\' data-trigger=\'hover\'></i>'" padding="false" otherClasses="h-200">
                <x-table>
                    @forelse ($pendingTasks as $task)
                        <tr>
                            <td class="pl-20">
                                <h5 class="f-13 text-darkest-grey"><a href="{{ route('tasks.show', [$task->id]) }}"
                                        class="openRightModal">{{ $task->heading }}</a></h5>
                                <div class="text-muted">{{ $task->project->project_name ?? '' }}</div>
                            </td>
                            <td>
                                @foreach ($task->users as $item)
                                    <div class="taskEmployeeImg rounded-circle mr-1">
                                        <a href="{{ route('employees.show', $item->id) }}">
                                            <img data-toggle="tooltip"
                                                data-original-title="{{ $item->name }}"
                                                src="{{ $item->image_url }}">
                                        </a>
                                    </div>
                                @endforeach
                            </td>
                            <td width="15%">@if(!is_null($task->due_date)) {{ $task->due_date->translatedFormat(company()->date_format) }} @else -- @endif</td>
                            <td class="f-14 pr-20 text-right" width="20%">
                                <x-status :style="'color:'.$task->boardColumn->label_color"
                                    :value="($task->boardColumn->slug == 'completed' || $task->boardColumn->slug == 'incomplete' ? __('app.' . $task->boardColumn->slug) : $task->boardColumn->column_name)" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="shadow-none">
                                <x-cards.no-record icon="tasks" :message="__('messages.noRecordFound')" />
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-cards.data>
        </div>
    @endif

    @if (in_array('leads', user_modules()) && in_array('pending_follow_up', $activeWidgets))
        <div class="col-sm-12 col-lg-6 mt-3">
            <x-cards.data :title="__('modules.dashboard.pendingFollowUp').' <i class=\'fa fa-question-circle\' data-toggle=\'popover\' data-placement=\'top\' data-content=\''.__('app.from').' '.$startDate->translatedFormat(company()->date_format).' '.__('app.to').' '.$endDate->translatedFormat(company()->date_format).'\' data-trigger=\'hover\'></i>'" padding="false" otherClasses="h-200">
                <x-table>
                    @forelse ($pendingLeadFollowUps as $item)
                        <tr>
                            <td class="pl-20">
                                <h5 class="f-13 text-darkest-grey"><a
                                        href="{{ route('leads.show', [$item->id]) }}">{{ $item->client_name }}</a>
                                </h5>
                                <div class="text-muted">{{ $item->company_name }}</div>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($item->follow_up_date_past)->timezone(company()->timezone)->translatedFormat(company()->date_format) }}
                            </td>
                            <td class="pr-20 text-right">
                                @if ($item->agent_id)
                                    <x-employee :user="$item->leadAgent->user" />
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="shadow-none">
                                <x-cards.no-record icon="users" :message="__('messages.noRecordFound')"></x-cards.no-record>
                            </td>
                        </tr>
                    @endforelse
                </x-table>
            </x-cards.data>
        </div>
    @endif

    @if (in_array('projects', user_modules()) && in_array('project_activity_timeline', $activeWidgets))
        <div class="col-sm-12 col-lg-6 mt-3">
            <x-cards.data :title="__('modules.dashboard.projectActivityTimeline').' <i class=\'fa fa-question-circle\' data-toggle=\'popover\' data-placement=\'top\' data-content=\''.__('app.from').' '.$startDate->translatedFormat(company()->date_format).' '.__('app.to').' '.$endDate->translatedFormat(company()->date_format).'\' data-trigger=\'hover\'></i>'" padding="false"
                otherClasses="h-200 p-activity-detail cal-info">
                @forelse($projectActivities as $key=>$activity)
                    <div class="card border-0 b-shadow-4 p-20 rounded-0">
                        <div class="card-horizontal">
                            <div class="card-header m-0 p-0 bg-white rounded">
                                <x-date-badge :month="$activity->created_at->timezone(company()->timezone)->translatedFormat('M')"
                                    :date="$activity->created_at->timezone(company()->timezone)->translatedFormat('d')">
                                </x-date-badge>
                            </div>
                            <div class="card-body border-0 p-0 ml-3">
                                <h4 class="card-title f-14 font-weight-normal text-capitalize mb-0">
                                    {!! __($activity->activity) !!}
                                </h4>
                                <p class="card-text f-12 text-dark-grey">
                                    <a href="{{ route('projects.show', $activity->project_id) }}"
                                        class="text-lightest font-weight-normal text-capitalize f-12">{{ $activity->project->project_name }}
                                    </a>
                                    <br>
                                    {{ $activity->created_at->timezone(company()->timezone)->translatedFormat(company()->time_format) }}
                                </p>
                            </div>
                        </div>
                    </div><!-- card end -->
                @empty
                    <div class="card border-0 p-20 rounded-0">
                        <x-cards.no-record icon="tasks" :message="__('messages.noRecordFound')" />
                    </div><!-- card end -->
                @endforelse
            </x-cards.data>
        </div>
    @endif

    @if (in_array('employees', user_modules()) && in_array('user_activity_timeline', $activeWidgets))
        <div class="col-sm-12 col-lg-6 mt-3">
            <x-cards.data :title="__('modules.dashboard.userActivityTimeline').' <i class=\'fa fa-question-circle\' data-toggle=\'popover\' data-placement=\'top\' data-content=\''.__('app.from').' '.$startDate->translatedFormat(company()->date_format).' '.__('app.to').' '.$endDate->translatedFormat(company()->date_format).'\' data-trigger=\'hover\'></i>'" padding="false"
                otherClasses="h-200 p-activity-detail cal-info">
                @forelse($userActivities as $key=>$activity)
                    <div class="card border-0 b-shadow-4 p-20 rounded-0">
                        <div class="card-horizontal">
                            <div class="card-header m-0 p-0 bg-white rounded">
                                <x-date-badge :month="$activity->created_at->timezone(company()->timezone)->translatedFormat('M')"
                                    :date="$activity->created_at->timezone(company()->timezone)->translatedFormat('d')">
                                </x-date-badge>
                            </div>
                            <div class="card-body border-0 p-0 ml-3">
                                <h4 class="card-title f-14 font-weight-normal text-capitalize mb-0">
                                    {!! __($activity->activity) !!}
                                </h4>
                                <p class="card-text f-12 text-dark-grey">
                                    <a href="{{ route('employees.show', $activity->user_id) }}"
                                        class="text-lightest font-weight-normal text-capitalize f-12">{{ $activity->user->name }}
                                    </a>
                                    <br>
                                    {{ $activity->created_at->timezone(company()->timezone)->translatedFormat(company()->time_format) }}
                                </p>
                            </div>
                        </div>
                    </div><!-- card end -->
                @empty
                    <div class="card border-0 p-20 rounded-0">
                        <x-cards.no-record icon="users" :message="__('messages.noRecordFound')" />
                    </div><!-- card end -->
                @endforelse
            </x-cards.data>
        </div>
    @endif
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    $('body').on('click', '.leave-action-approved', function() {
        let action = $(this).data('leave-action');
        let leaveId = $(this).data('leave-id');
        var type = $(this).data('type');
            if(type == undefined){
                var type = 'single';
            }
        let searchQuery = "?leave_action=" + action + "&leave_id=" + leaveId + "&type=" + type;
        let url = "{{ route('leaves.show_approved_modal') }}" + searchQuery;

        $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
        $.ajaxModal(MODAL_LG, url);
    });


    $('#save-dashboard-widget').click(function() {
        $.easyAjax({
            url: "{{ route('dashboard.widget', 'admin-dashboard') }}",
            container: '#dashboardWidgetForm',
            blockUI: true,
            type: "POST",
            redirect: true,
            data: $('#dashboardWidgetForm').serialize(),
            success: function() {
                window.location.reload();
            }
        })
    });
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
                toastr.success('Attendance approved successfully!');
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
                toastr.success('Attendance Rejected successfully!');
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
     $('body').on('click', '.reimbursement-action-rejected,.reimbursement-action-approved', function () {
        var reimbursementId = $(this).data('reimbursement-id');
        var action=$(this).data('reimbursement-action');
        $.ajax({
            url: '{{ route('reimbursement.approved','') }}/'+reimbursementId,
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
     $('body').on('click', '.asset-action-rejected,.asset-action-approved', function () {
        var assetId = $(this).data('asset-id');
        var action=$(this).data('asset-action');
        $.ajax({
            url: '{{ route('asset.status','') }}/'+assetId,
            type: 'post',
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

