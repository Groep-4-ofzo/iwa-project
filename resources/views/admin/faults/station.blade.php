@extends('admin.layouts.admin')

@section('content')

@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        const pastHour = @json($pastHour ?? null);
        const recentFaults = @json($recentFaults ?? []);

        const faultTypeBreakdown = @json($faultTypeBreakdown ?? []);

        const faultTypeChart = {
            labels: faultTypeBreakdown.map((r) => r.fault_type),
            datasets: [
                {
                    label: "Faults by type (past hour)",
                    data: faultTypeBreakdown.map((r) => r.total),
                    backgroundColor: [
                        "rgba(234, 179, 8, 0.7)",
                        "rgba(239, 68, 68, 0.7)",
                        "rgba(107, 114, 128, 0.7)",
                    ],
                    borderColor: [
                        "rgba(234, 179, 8, 1)",
                        "rgba(239, 68, 68, 1)",
                        "rgba(107, 114, 128, 1)",
                    ],
                    borderWidth: 1,
                },
            ],
        };

        window.__charts = {
            charts: [
                {
                    canvasId: "faultStationFaultsByTime",
                    type: "line",
                    time: { parser: "yyyy-MM-dd HH:mm:ss" },
                    data: { datasets: [pastHour?.faultCountBy5Minutes ?? null] },
                    options: {
                        plugins: {
                            legend: { display: false },
                            title: { display: false },
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    },
                },
                {
                    canvasId: "faultStationFaultTypes",
                    type: "bar",
                    data: faultTypeChart,
                    options: {
                        plugins: {
                            legend: { display: false },
                            title: { display: false },
                        },
                        scales: {
                            x: { grid: { display: false } },
                            y: { beginAtZero: true },
                        },
                    },
                },
            ].filter((c) => c?.data && (c.data.datasets?.[0] || c.data.labels)),
        };
    </script>
@endif

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">Faults in Station: {{ $station }}</h1>
        <div class="text-sm text-gray-500">Past hour window</div>
    </div>

    <div class="flex items-center gap-3">
        <a href="/station/{{ $station }}"
           class="inline-flex items-center px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800">
            Go to station
        </a>
        <a href="{{ route('admin.faults.index') }}"
           class="inline-flex items-center px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-gray-800">
            Back to dashboard
        </a>

    </div>
</div>

<div class="grid grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded shadow p-4 col-span-2">
        <div class="flex items-center justify-between mb-2">
            <div class="font-semibold">Faults over time (past hour)</div>
        </div>
        <div style="height: 260px;">
            <canvas id="faultStationFaultsByTime"></canvas>
        </div>
    </div>

    <div class="bg-white rounded shadow p-4">
        <div class="font-semibold mb-2">Past hour metrics</div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <div class="text-sm text-gray-500">Measurements</div>
                <div class="text-2xl font-bold">{{ $pastHour['total_measurements'] ?? 0 }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">Faults</div>
                <div class="text-2xl font-bold">{{ $pastHour['faults'] ?? 0 }}</div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded shadow p-4">
        <div class="font-semibold mb-2">Fault types (past hour)</div>
        <div style="height: 260px;">
            <canvas id="faultStationFaultTypes"></canvas>
        </div>

        @if(!empty($faultTypeBreakdown))
            <div class="mt-4">
                <div class="text-sm text-gray-500 mb-1">Counts</div>
                <ul class="text-sm">
                    @foreach($faultTypeBreakdown as $row)
                        <li class="flex items-center justify-between border-b py-1">
                            <span class="text-gray-800">{{ $row['fault_type'] ?? '-' }}</span>
                            <span class="font-medium">{{ $row['total'] ?? 0 }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="bg-white rounded shadow p-4 col-span-2">
        <div class="flex items-center justify-between mb-2">
            <div class="font-semibold">Recent faults & problem</div>
            <div class="text-sm text-gray-500">Most recent first</div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">Timestamp</th>
                    <th class="p-3">Problem</th>
                    <th class="p-3">Details</th>
                    <th class="p-3">Corrected value</th>
                </tr>
                </thead>
                <tbody>
                @forelse(($recentFaults ?? []) as $fault)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">
                            {{ ($fault['date'] ?? '-') }} {{ ($fault['time'] ?? '-') }}
                        </td>
                        <td class="p-3">
                            @if(($fault['fault_type'] ?? null) === 'Missing Field')
                                <span class="text-yellow-600 font-semibold">Missing Field</span>
                            @elseif(($fault['fault_type'] ?? null) === 'Invalid Temperature')
                                <span class="text-red-600 font-semibold">Invalid Temperature</span>
                            @else
                                <span class="text-gray-600 font-semibold">Unknown</span>
                            @endif
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            {{ $fault['details'] ?? '-' }}
                        </td>
                        <td class="p-3 text-sm text-gray-700">
                            {{ $fault['corrected_value'] ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-500">
                            No faults found in the past hour.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
