<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use App\Models\OriginalMeasurement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FaultController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        $totalFaults = OriginalMeasurement::join(
            'measurement',
            'original_measurement.corrected_measurement',
            '=',
            'measurement.id'
        )
            ->where('measurement.date', $today)
            ->count();

        $worstStationData = OriginalMeasurement::join(
            'measurement',
            'original_measurement.corrected_measurement',
            '=',
            'measurement.id'
        )
            ->select(
                'measurement.station',
                DB::raw('COUNT(*) as faults')
            )
            ->where('measurement.date', $today)
            ->groupBy('measurement.station')
            ->orderByDesc('faults')
            ->first();

        $worstStation = $worstStationData?->station ?? 'Geen data';
        $worstStationFaults = $worstStationData?->faults ?? 0;

        $totalMeasurements = Measurement::where('date', $today)->count();

        $correctPercentage = $totalMeasurements > 0
            ? round((($totalMeasurements - $totalFaults) / $totalMeasurements) * 100, 2)
            : 100;

        $mostCommonFault = OriginalMeasurement::join(
            'measurement',
            'original_measurement.corrected_measurement',
            '=',
            'measurement.id'
        )
            ->selectRaw("
                CASE
                    WHEN missing_field IS NOT NULL THEN 'Missing Field'
                    WHEN invalid_temperature IS NOT NULL THEN 'Invalid Temperature'
                END as fault_type,
                COUNT(*) as total
            ")
            ->where('measurement.date', $today)
            ->groupBy('fault_type')
            ->orderByDesc('total')
            ->first();

        $latestFaults = OriginalMeasurement::join(
            'measurement',
            'original_measurement.corrected_measurement',
            '=',
            'measurement.id'
        )
            ->select(
                'measurement.station',
                'measurement.date',
                'measurement.time',
                'original_measurement.missing_field',
                'original_measurement.invalid_temperature'
            )
            ->where('measurement.date', $today)
            ->orderByDesc('measurement.time')
            ->limit(20)
            ->get();

        return view('admin.faults.index', compact(
            'totalFaults',
            'worstStation',
            'worstStationFaults',
            'correctPercentage',
            'mostCommonFault',
            'latestFaults'
        ));
    }
}
