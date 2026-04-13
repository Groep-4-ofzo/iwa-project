<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} | Mijn Stations</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            body { font-family: Arial, sans-serif; background-color: #f3f4f6; padding: 32px; }
        </style>
    @endif
</head>
<body class="bg-gray-100 min-h-screen p-8">

<div class="max-w-3xl mx-auto">

    <a href="{{ url('/') }}"
       class="inline-flex items-center gap-2 mb-6 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg shadow-sm hover:bg-gray-300 transition">
        ← Terug naar startpagina
    </a>

    <div class="flex items-center gap-3 mb-8">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8">
        <h1 class="text-4xl font-bold">Mijn stations</h1>
    </div>

    <form method="GET" action="{{ url('/stations') }}" class="mb-4">
        <div class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Zoek op naam..."
                   class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring focus:border-blue-300">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Zoeken
            </button>
            @if(request('search'))
                <a href="{{ url('/stations') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    ✕
                </a>
            @endif
        </div>
    </form>

    <div class="bg-white rounded-lg shadow overflow-hidden mb-4">
        @forelse($stations as $station)
            <a href="{{ url('/station/' . $station->name) }}"
               class="flex items-center justify-between px-6 py-4 border-b last:border-0 hover:bg-gray-50 transition">
                <span class="font-medium text-gray-800">{{ $station->name }}</span>
                <span class="text-gray-400">→</span>
            </a>
        @empty
            <p class="px-6 py-4 text-gray-500">Geen stations gevonden.</p>
        @endforelse
    </div>

    {{ $stations->links() }}

</div>

</body>
</html>
