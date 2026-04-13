@extends('admin.layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Resultaten: {{ $queryRecord->omschrijving }}</h1>
            <p class="text-gray-500 mt-1">Gevonden: {{ $results->total() }} stations</p>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">Naam</th>
                    <th class="px-4 py-3 text-center">Landcode</th>
                    <th class="px-4 py-3">Longitude</th>
                    <th class="px-4 py-3">Latitude</th>
                    <th class="px-4 py-3">Elevation</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @foreach($results as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $row->name }}</td>
                        <td class="px-4 py-3 text-center font-semibold">{{ $row->geolocation->first()->country_code ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $row->longitude }}</td>
                        <td class="px-4 py-3">{{ $row->latitude }}</td>
                        <td class="px-4 py-3">{{ $row->elevation }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center mb-4">
            <a href="{{ route('admin.query.index') }}" class="text-blue-600 hover:underline">
                ← Terug naar de Builder
            </a>
            {{ $results->links() }}
        </div>
    </div>
@endsection
