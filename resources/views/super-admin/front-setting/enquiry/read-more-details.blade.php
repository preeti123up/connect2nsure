

<x-table class="table-sm-responsive table mb-0">
    <x-slot name="thead">
    <th>Name</th>
        <th>Company name</th>
        <th>Mobile</th>
        <th>Email</th>
        <th>Company Size</th>
        <th>Message</th>
  
    </x-slot>

    @forelse($details as $equiry)
        <tr class="row{{ $equiry->id }}">
            <td>{{ $equiry->name }}</td>
            <td>{{ $equiry->company_name }}</td>
            <td>{{ $equiry->mobile }}</td>
            <td>{{ $equiry->email }}</td>
            <td>{{ $equiry->company_size }}</td>
            <td>{{ $equiry->message }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="4">
                <x-cards.no-record icon="list" :message="__('messages.noRecordFound')"/>
            </td>
        </tr>
    @endforelse

</x-table>
