@extends('admin.layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Subscriptions</h1>
        <a href="{{ route('admin.subscriptions.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">New Subscription</a>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">bedrijf</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">subscription type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">start datum</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">end datum</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">prijs</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">notes</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">identifier</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">token</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">

                @foreach($subscriptions as $subscription)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->getCompany->name ?? '-' }} </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->subscriptionType->name ?? '-' }} </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->start_date}}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->end_date}}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->price}}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->notes}}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->identifier}}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->token}}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <a href="{{ route('admin.subscriptions.edit', $subscription) }}" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                        <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" method="POST" class="inline">
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
        {{ $subscriptions->links() }}
    </div>
</div>
@endsection