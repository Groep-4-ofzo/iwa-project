@extends('admin.layouts.admin')

@section('content')

<h1 class="text-2xl font-bold mb-6">Fault Dashboard</h1>

<div class="grid grid-cols-5 gap-4 mb-8">

    <div class="bg-white p-4 rounded shadow">
        <div class="text-gray-500 text-sm">Faults today</div>
        <div class="text-2xl font-bold">{{ $totalFaults }}</div>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <div class="text-gray-500 text-sm">Worst station</div>
        <div class="text-2xl font-bold">{{ $worstStation }}</div>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <div class="text-gray-500 text-sm">Faults worst station</div>
        <div class="text-2xl font-bold">{{ $worstStationFaults }}</div>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <div class="text-gray-500 text-sm">Correct data %</div>
        <div class="text-2xl font-bold text-green-600">{{ $correctPercentage }}%</div>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <div class="text-gray-500 text-sm">Most common fault</div>
        <div class="text-2xl font-bold">
            {{ $mostCommonFault?->fault_type ?? 'Geen data' }}
        </div>
    </div>

</div>

<div class="bg-white rounded shadow">

    <div class="p-4 border-b font-semibold">
        Latest Faults
    </div>

    <table class="w-full text-left">
        <thead class="bg-gray-100">
        <tr>
            <th class="p-3">Station</th>
            <th class="p-3">Fault Type</th>
            <th class="p-3">Missing Field</th>
            <th class="p-3">Invalid Temp</th>
            <th class="p-3">Date</th>
            <th class="p-3">Time</th>
        </tr>
        </thead>

        <tbody>
        @forelse($latestFaults as $fault)
        <tr class="border-b hover:bg-gray-50">

            <td class="p-3">{{ $fault->station }}</td>

            <td class="p-3">
                @if($fault->missing_field)
                <span class="text-yellow-600 font-semibold">
                                Missing Field
                            </span>
                @elseif($fault->invalid_temperature)
                <span class="text-red-600 font-semibold">
                                Invalid Temperature
                            </span>
                @else
                <span class="text-gray-500">
                                Unknown
                            </span>
                @endif
            </td>

            <td class="p-3">
                {{ $fault->missing_field ?? '-' }}
            </td>

            <td class="p-3">
                {{ $fault->invalid_temperature ?? '-' }}
            </td>

            <td class="p-3">
                {{ $fault->date }}
            </td>

            <td class="p-3">
                {{ $fault->time }}
            </td>

        </tr>
        @empty
        <tr>
            <td colspan="6" class="p-4 text-center text-gray-500">
                Geen faults gevonden vandaag
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>

</div>

@endsection
