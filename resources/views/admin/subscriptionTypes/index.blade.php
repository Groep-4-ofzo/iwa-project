@extends('admin.layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Subscription Types</h1>
        <a href="{{ route('admin.subscriptionTypes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">New Subscription Type</a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nr. Stations</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Freq. (hours)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Freq. (days)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Continuous</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price / Station</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valid Through</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">

                @foreach($subscriptionTypes as $type)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $type->name }}</td>
                    <td class="px-6 py-4">{{ $type->description ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $type->nr_stations ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $type->frequency_in_hours ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $type->frequency_in_days ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($type->continuous)
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Yes</span>
                        @else
                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded">No</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">€ {{ number_format($type->price_per_station, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $type->valid_through ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <a href="{{ route('admin.subscriptionTypes.edit', $type) }}" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                        <form action="{{ route('admin.subscriptionTypes.destroy', $type) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Weet je het zeker?')" class="text-red-600 hover:text-red-800">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $subscriptionTypes->links() }}
    </div>
</div>
@endsection