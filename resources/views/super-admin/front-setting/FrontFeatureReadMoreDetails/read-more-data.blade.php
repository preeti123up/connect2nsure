<x-table class="table-sm-responsive table mb-0">
    <x-slot name="thead">
        <th>@lang('app.title')</th>
        <th>@lang('app.description')</th>
        <th>Image</th>
        <th class="text-right">@lang('app.action')</th>
    </x-slot>

    @forelse($details as $feature)
        <tr class="row{{ $feature->id }}">
            <td>{{ $feature->title }}</td>
                <td>{!! mb_strimwidth($feature->description, 0, 50, '...')  !!}</td>
            <td>
             <img style="max-height: 40px" src="{{asset("user-uploads/front/read-more/".$feature->image) }}" alt=""/>
                   
                </td>
            <td class="text-right">
                <div class="task_view">
                    <a class="task_view_more d-flex align-items-center justify-content-center edit-feature"
                       data-id="{{$feature->id}}" data-type="{{$type}}">
                        <i class="fa fa-edit icons mr-2"></i> @lang('app.edit')
                    </a>
                </div>
                <div class="task_view mt-1 mt-lg-0 mt-md-0">
                    <a class="task_view_more d-flex align-items-center justify-content-center read-more-table-row"
                       href="javascript:;" data-id="{{ $feature->id }}" data-type="{{$type}}">
                        <i class="fa fa-trash icons mr-2"></i> @lang('app.delete')
                    </a>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4">
                <x-cards.no-record icon="list" :message="__('messages.noRecordFound')"/>
            </td>
        </tr>
    @endforelse

</x-table>
