@extends('admin.layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-medium text-gray-900">Subscriptions</h1>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg mb-5">
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Identifier</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Type</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Prijs</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Acties</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($subscriptions as $subscription)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4 text-sm font-medium text-gray-900">
                        {{ $subscription->identifier }}
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-500">
                        {{ $subscription->subscriptionType->name ?? '-' }}
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-500">
                        {{ $subscription->price ? '€' . number_format($subscription->price, 2, ',', '.') : '-' }}
                    </td>
                    <td class="px-5 py-4 text-sm">
                        <a href="{{ route('admin.subscriptionStations.show', $subscription) }}"
                           class="text-xs px-3 py-1.5 rounded-md border border-gray-200 text-gray-600 hover:bg-gray-100 transition-colors">
                            Bekijk stations
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-10 text-center text-sm text-gray-400">
                        Geen abonnementen gevonden.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($subscriptions->hasPages())
    <div class="mt-4">
        {{ $subscriptions->links() }}
    </div>
    @endif

</div>
@endsection
