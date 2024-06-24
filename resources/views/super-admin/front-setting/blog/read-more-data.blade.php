<x-table class="table-sm-responsive table mb-0">
    <x-slot name="thead">
        <th>@lang('app.title')</th>
        <th>@lang('app.description')</th>
        <th>Image</th>
        <th>Video Link</th>
        <th class="text-right">@lang('app.action')</th>
    </x-slot>

    @forelse($details as $blog)
        <tr class="row{{ $blog->id }}">
            <td>{{ $blog->title }}</td>
                <td><p>{!! mb_strimwidth($blog->description, 0, 10, '...')  !!}</p></td>
            <td>
             <img style="max-height: 40px" src="{{asset("user-uploads/front/blog/".$blog->image) }}" alt=""/>
                   
                </td>
                <td>
                {{ $blog->video }}
                </td>
            <td class="text-right">
                <div class="task_view">
                    <a class="task_view_more d-flex align-items-center justify-content-center edit-blog"
                       data-id="{{$blog->id}}" data-type="{{$type}}">
                        <i class="fa fa-edit icons mr-2"></i> @lang('app.edit')
                    </a>
                </div>
                <div class="task_view mt-1 mt-lg-0 mt-md-0">
                    <a class="task_view_more d-flex align-items-center justify-content-center read-more-table-row"
                       href="javascript:;" data-id="{{ $blog->id }}" data-type="{{$type}}">
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
