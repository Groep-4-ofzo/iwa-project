<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} | Station {{ $station->name }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            window.__charts = {
                charts: [
                    {
                        canvasId: "stationChartHourTemp",
                        type: "line",
                        time: { parser: "yyyy-MM-dd HH:mm:ss" },
                        data: { datasets: [@json($chartTemp)] },
                        options: {
                            plugins: {
                                legend: { display: false },
                                title: { display: true, text: "Temperature Last hour" },
                            },
                            scales: { y: { ticks: { callback: (value) => parseFloat(value).toFixed(1) + "°C" } } },
                        },
                    },
                    {
                        canvasId: "stationChartHourAirPressure",
                        type: "line",
                        time: { parser: "yyyy-MM-dd HH:mm:ss" },
                        data: { datasets: [@json($chartAirPressure)] },
                        options: {
                            plugins: {
                                legend: { display: false },
                                title: { display: true, text: "Air Pressure Last hour" },
                            },
                            scales: { y: { beginAtZero: false, ticks: { callback: (value) => parseFloat(value).toFixed(2) + " hPa" } } },
                        },
                    },
                    {
                        canvasId: "stationChartHourWindSpeed",
                        type: "line",
                        time: { parser: "yyyy-MM-dd HH:mm:ss" },
                        data: { datasets: [@json($chartWindSpeed)] },
                        options: {
                            plugins: {
                                legend: { display: false },
                                title: { display: true, text: "Wind Speed Last hour" },
                            },
                            scales: { y: { beginAtZero: true, ticks: { callback: (value) => parseFloat(value).toFixed(1) + " m/s" } } },
                        },
                    },
                    {
                        canvasId: "stationChartHourPercipation",
                        type: "line",
                        time: { parser: "yyyy-MM-dd HH:mm:ss" },
                        data: { datasets: [@json($chartPercipation)] },
                        options: {
                            plugins: {
                                legend: { display: false },
                                title: { display: true, text: "Precipitation Last hour" },
                            },
                            scales: { y: { beginAtZero: true, ticks: { callback: (value) => parseFloat(value).toFixed(2) + " mm" } } },
                        },
                    },
                ],
            };
        </script>
    @else
        <style>
            body { font-family: Arial, sans-serif; background-color: #f3f4f6; padding: 32px; }
        </style>
    @endif
</head>
<body class="bg-gray-100 min-h-screen p-8">

<div class="max-w-4xl mx-auto">

    <a href="{{ url('/') }}"
       class="inline-flex items-center gap-2 mb-6 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg shadow-sm hover:bg-gray-300 transition">
        ← Terug naar startpagina
    </a>

    <div class="flex items-center gap-3 mb-8">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8">
        <h1 class="text-4xl font-bold">Station {{ $station->name }}</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold border-b pb-2 mb-4">Stationgegevens</h2>
            <ul class="space-y-1 text-sm text-gray-600">
                <li><span class="font-medium">Latitude:</span> {{ $stats['station']['latitude'] ?? $station->latitude ?? '-' }}</li>
                <li><span class="font-medium">Longitude:</span> {{ $stats['station']['longitude'] ?? $station->longitude ?? '-' }}</li>
                <li><span class="font-medium">Elevation:</span> {{ $stats['station']['elevation'] ?? $station->elevation ?? '-' }}</li>
            </ul>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold border-b pb-2 mb-4">Locatie</h2>
            @if($geo)
                <ul class="space-y-1 text-sm text-gray-600">
                    <li><span class="font-medium">Land:</span> {{ $geo->country ?? '-' }}</li>
                    <li><span class="font-medium">Regio:</span> {{ $geo->region ?? '-' }}</li>
                    <li><span class="font-medium">Stad:</span> {{ $geo->city ?? '-' }}</li>
                    <li><span class="font-medium">Plaats:</span> {{ $geo->place ?? '-' }}</li>
                    <li><span class="font-medium">Postcode:</span> {{ $geo->postcode ?? '-' }}</li>
                </ul>
            @else
                <p class="text-sm text-gray-500">Geen locatiegegevens gevonden voor dit station.</p>
            @endif
        </div>

    </div>

    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-lg font-semibold border-b pb-2 mb-4">Laatste meting</h2>
        @if($stats['latest'])
            <div class="grid grid-cols-2 gap-x-8 gap-y-1 text-sm text-gray-600">
                <div><span class="font-medium">Tijdstip:</span> {{ $stats['latest']['date'] }} {{ $stats['latest']['time'] }}</div>
                <div><span class="font-medium">Temperatuur:</span> {{ $stats['latest']['temperature'] ?? '-' }}</div>
                <div><span class="font-medium">Dauwpunt:</span> {{ $stats['latest']['dewpoint_temperature'] ?? '-' }}</div>
                <div><span class="font-medium">Druk (station):</span> {{ $stats['latest']['air_pressure_station'] ?? '-' }}</div>
                <div><span class="font-medium">Druk (zeeniveau):</span> {{ $stats['latest']['air_pressure_sea_level'] ?? '-' }}</div>
                <div><span class="font-medium">Zichtbaarheid:</span> {{ $stats['latest']['visibility'] ?? '-' }}</div>
                <div><span class="font-medium">Wind:</span> {{ $stats['latest']['wind_speed'] ?? '-' }} (richting {{ $stats['latest']['wind_direction'] ?? '-' }})</div>
                <div><span class="font-medium">Neerslag:</span> {{ $stats['latest']['percipation'] ?? '-' }}</div>
                <div><span class="font-medium">Sneeuwdiepte:</span> {{ $stats['latest']['snow_depth'] ?? '-' }}</div>
                <div><span class="font-medium">Omstandigheden:</span> {{ $stats['latest']['conditions'] ?? '-' }}</div>
                <div><span class="font-medium">Bewolking:</span> {{ $stats['latest']['cloud_cover'] ?? '-' }}</div>
            </div>
        @else
            <p class="text-sm text-gray-500">Geen metingen beschikbaar voor dit station.</p>
        @endif
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold border-b pb-2 mb-4">Laatste 24 uur</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div style="height: 260px;"><canvas id="stationChartHourTemp"></canvas></div>
            <div style="height: 260px;"><canvas id="stationChartHourAirPressure"></canvas></div>
            <div style="height: 260px;"><canvas id="stationChartHourWindSpeed"></canvas></div>
            <div style="height: 260px;"><canvas id="stationChartHourPercipation"></canvas></div>
        </div>
    </div>

</div>

</body>
</html>
