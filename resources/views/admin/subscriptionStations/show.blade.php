@extends('admin.layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.subscriptionStations.index') }}"
           class="text-sm text-gray-400 hover:text-gray-600 transition-colors">
            ← Terug
        </a>
        <span class="text-gray-200">/</span>
        <h1 class="text-xl font-medium text-gray-900">{{ $subscription->identifier }}</h1>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg mb-5">
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="flex gap-6 items-start">

        {{-- Gekoppelde stations --}}
        <div class="flex-1">
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Station</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Acties</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($subscriptionStations as $subscriptionStation)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-4 text-sm font-medium text-gray-900">
                                {{ $subscriptionStation->getStation->name ?? $subscriptionStation->station }}
                            </td>
                            <td class="px-5 py-4 text-sm">
                                <form action="{{ route('admin.subscriptionStations.destroy', [$subscriptionStation->station, $subscriptionStation->subscription]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Weet je zeker dat je deze koppeling wilt verwijderen?')"
                                            class="text-xs px-3 py-1.5 rounded-md border border-red-100 text-red-500 hover:bg-red-50 transition-colors">
                                        Verwijder
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-5 py-10 text-center text-sm text-gray-400">
                                Geen stations gekoppeld aan dit abonnement.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Formulier --}}
        <div class="w-80 shrink-0">
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h2 class="text-sm font-medium text-gray-900 mb-4">Station toevoegen</h2>

                @if($errors->any())
                <div class="bg-red-50 border border-red-100 text-red-600 text-sm px-4 py-3 rounded-lg mb-4">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if($stations->isEmpty())
                <p class="text-sm text-gray-400">Alle stations zijn al gekoppeld.</p>
                @else
                <form action="{{ route('admin.subscriptionStations.store', $subscription) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="station_id" class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1.5">
                            Station(s)
                        </label>
                        <select name="station_id[]" id="station_id" multiple
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition min-h-36">
                            @foreach($stations as $station)
                                <option value="{{ $station->name }}"
                                    {{ in_array($station->name, old('station_id', [])) ? 'selected' : '' }}>
                                    {{ $station->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Houd Ctrl (of ⌘) ingedrukt voor meerdere.</p>
                    </div>

                    <button type="submit"
                            class="w-full bg-gray-900 hover:bg-gray-700 text-white text-sm px-4 py-2.5 rounded-lg transition-colors">
                        Stations koppelen
                    </button>
                </form>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection