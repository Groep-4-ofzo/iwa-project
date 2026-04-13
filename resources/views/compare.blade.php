<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vergelijk Stations</title>
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
        <h1 class="text-4xl font-bold">Vergelijk twee weerstations</h1>
    </div>

    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <form method="POST" action="/compare" class="flex gap-4 items-end">
            @csrf
            <div class="flex-1">
                <label class="block font-medium mb-1 text-gray-700">Station 1</label>
                <select name="station1" class="w-full border rounded px-3 py-2">
                    @foreach($stations as $station)
                        <option value="{{ $station->name }}">{{ $station->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label class="block font-medium mb-1 text-gray-700">Station 2</label>
                <select name="station2" class="w-full border rounded px-3 py-2">
                    @foreach($stations as $station)
                        <option value="{{ $station->name }}">{{ $station->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Vergelijk
            </button>
        </form>
    </div>

    @isset($station1, $station2)
        <div class="grid grid-cols-2 gap-6">

            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold border-b pb-2 mb-4">{{ $station1 }}</h2>
                @isset($data1)
                    <ul class="space-y-1 text-gray-700">
                        <li><span class="font-medium">Longitude:</span> {{ $data1->longitude }}</li>
                        <li><span class="font-medium">Latitude:</span> {{ $data1->latitude }}</li>
                        <li><span class="font-medium">Elevation:</span> {{ $data1->elevation }}</li>
                    </ul>
                @endisset
                @if($geo1)
                    <ul class="space-y-1 text-gray-700 mt-3">
                        <li><span class="font-medium">Land:</span> {{ $geo1->country }}</li>
                        <li><span class="font-medium">Stad:</span> {{ $geo1->city }}</li>
                        <li><span class="font-medium">Regio:</span> {{ $geo1->region }}</li>
                    </ul>
                @endif
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold border-b pb-2 mb-4">{{ $station2 }}</h2>
                @isset($data2)
                    <ul class="space-y-1 text-gray-700">
                        <li><span class="font-medium">Longitude:</span> {{ $data2->longitude }}</li>
                        <li><span class="font-medium">Latitude:</span> {{ $data2->latitude }}</li>
                        <li><span class="font-medium">Elevation:</span> {{ $data2->elevation }}</li>
                    </ul>
                @endisset
                @if($geo2)
                    <ul class="space-y-1 text-gray-700 mt-3">
                        <li><span class="font-medium">Land:</span> {{ $geo2->country }}</li>
                        <li><span class="font-medium">Stad:</span> {{ $geo2->city }}</li>
                        <li><span class="font-medium">Regio:</span> {{ $geo2->region }}</li>
                    </ul>
                @endif
            </div>

        </div>
    @endisset

</div>

</body>
</html>
