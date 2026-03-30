<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }} | Station {{ $station->name }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            window.__stationCharts = {
                hour: {
                    temp: @json($chartTemp),
                    airPressure: @json($chartAirPressure),
                    windSpeed: @json($chartWindSpeed),
                    percipation: @json($chartPercipation),
                },
            };
        </script>
    @endif
</head>

<body>
<main class="flex-1 p-6 overflow-y-auto">
    <h1 class="mb-4">Station {{ $station->name }}</h1>

    <section class="mb-6">
        <h2 class="mb-2 font-medium">Station details</h2>
        <ul class="text-sm text-[#706f6c]">
            <li>Latitude: {{ $stats['station']['latitude'] ?? $station->latitude ?? '-' }}</li>
            <li>Longitude: {{ $stats['station']['longitude'] ?? $station->longitude ?? '-' }}</li>
            <li>Elevation: {{ $stats['station']['elevation'] ?? $station->elevation ?? '-' }}</li>
        </ul>
    </section>

    <section class="mb-6">
        <h2 class="mb-2 font-medium">Location</h2>
        @if($geo)
            <ul class="text-sm text-[#706f6c]">
                <li>Country: {{ $geo->country ?? '-' }}</li>
                <li>Region: {{ $geo->region ?? '-' }}</li>
                <li>City: {{ $geo->city ?? '-' }}</li>
                <li>Place: {{ $geo->place ?? '-' }}</li>
                <li>Postcode: {{ $geo->postcode ?? '-' }}</li>
            </ul>
        @else
            <p class="text-sm text-[#706f6c]">No geolocation found for this station.</p>
        @endif
    </section>

    <section class="mb-6">
        <h2 class="mb-2 font-medium">Latest measurement</h2>
        @if($stats['latest'])
            <ul class="text-sm text-[#706f6c]">
                <li>Timestamp: {{ $stats['latest']['date'] }} {{ $stats['latest']['time'] }}</li>
                <li>Temperature: {{ $stats['latest']['temperature'] ?? '-' }}</li>
                <li>Dewpoint: {{ $stats['latest']['dewpoint_temperature'] ?? '-' }}</li>
                <li>Pressure (station): {{ $stats['latest']['air_pressure_station'] ?? '-' }}</li>
                <li>Pressure (sea level): {{ $stats['latest']['air_pressure_sea_level'] ?? '-' }}</li>
                <li>Visibility: {{ $stats['latest']['visibility'] ?? '-' }}</li>
                <li>Wind: {{ $stats['latest']['wind_speed'] ?? '-' }} (dir {{ $stats['latest']['wind_direction'] ?? '-' }})</li>
                <li>Precipitation: {{ $stats['latest']['percipation'] ?? '-' }}</li>
                <li>Snow depth: {{ $stats['latest']['snow_depth'] ?? '-' }}</li>
                <li>Conditions: {{ $stats['latest']['conditions'] ?? '-' }}</li>
                <li>Cloud cover: {{ $stats['latest']['cloud_cover'] ?? '-' }}</li>
            </ul>
        @else
            <p class="text-sm text-[#706f6c]">No measurements available for this station.</p>
        @endif
    </section>

    <section class="mb-6">
        <h3 class="mb-2 font-medium">Last 24 hours</h3>
        <div style="height: 260px;">
            <canvas id="stationChartHourTemp"></canvas>
        </div>
        <div style="height: 260px;">
            <canvas id="stationChartHourAirPressure"></canvas>
        </div>
        <div style="height: 260px;">
            <canvas id="stationChartHourWindSpeed"></canvas>
        </div>
        <div style="height: 260px;">
            <canvas id="stationChartHourPercipation"></canvas>
        </div>
    </section>
</main>
</body>
</html>
