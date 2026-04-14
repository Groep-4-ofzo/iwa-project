<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use App\Models\OriginalMeasurement;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class FaultStationController extends Controller
{
    public function index(string $station): View
    {
        $now = Carbon::now();
        $start = $now->copy()->subHour();

        $tsSql = "STR_TO_DATE(CONCAT(measurement.date,' ',measurement.time), '%Y-%m-%d %H:%i:%s')";

        $startStr = $start->format('Y-m-d H:i:s');
        $endStr = $now->format('Y-m-d H:i:s');

        $totalMeasurements = (int) Measurement::query()
            ->where('station', $station)
            ->whereRaw("$tsSql BETWEEN ? AND ?", [$startStr, $endStr])
            ->count();

        $faultsQuery = OriginalMeasurement::query()
            ->join('measurement', 'original_measurement.corrected_measurement', '=', 'measurement.id')
            ->where('measurement.station', $station)
            ->whereRaw("$tsSql BETWEEN ? AND ?", [$startStr, $endStr]);

        $totalFaults = (int) (clone $faultsQuery)->count();

        $bucketMinutes = 5;

        $bucketSql = "FROM_UNIXTIME(FLOOR(UNIX_TIMESTAMP($tsSql) / (? * 60)) * (? * 60))";
        $bucketTsSql = "DATE_FORMAT($bucketSql, '%Y-%m-%d %H:%i:00')";

        $rawBuckets = (clone $faultsQuery)
            ->selectRaw("$bucketTsSql as bucket_ts", [$bucketMinutes, $bucketMinutes])
            ->selectRaw('COUNT(*) as total_faults')
            ->groupBy('bucket_ts')
            ->orderBy('bucket_ts')
            ->get();

        $dataByTs = $rawBuckets->keyBy('bucket_ts');

        $filled = [];
        $cursor = $start->copy()->second(0);
        $cursor->minute((int) (floor($cursor->minute / $bucketMinutes) * $bucketMinutes));

        $stepSeconds = $bucketMinutes * 60;

        while ($cursor->lessThanOrEqualTo($now)) {
            $ts = $cursor->format('Y-m-d H:i:00');
            $filled[] = [
                'x' => $ts,
                'y' => (int) ($dataByTs[$ts]->total_faults ?? 0),
            ];
            $cursor->addSeconds($stepSeconds);
        }

        $faultTypeBreakdown = (clone $faultsQuery)
            ->selectRaw(
                "
                CASE
                    WHEN original_measurement.missing_field IS NOT NULL THEN 'Missing Field'
                    WHEN original_measurement.invalid_temperature IS NOT NULL THEN 'Invalid Temperature'
                    ELSE 'Unknown'
                END as fault_type,
                COUNT(*) as total
            ",
            )
            ->groupBy('fault_type')
            ->orderByDesc('total')
            ->get();

        $recentFaults = (clone $faultsQuery)
            ->select('measurement.date', 'measurement.time', 'original_measurement.missing_field', 'original_measurement.invalid_temperature', 'measurement.temperature')
            ->orderByDesc('measurement.date')
            ->orderByDesc('measurement.time')
            ->limit(25)
            ->get()
            ->map(function ($row) {
                $type = 'Unknown';
                $details = null;
                $correctedValue = null;

                if (! is_null($row->missing_field)) {
                    $type = 'Missing Field';
                    $details = $row->missing_field;
                } elseif (! is_null($row->invalid_temperature)) {
                    $type = 'Invalid Temperature';
                    $details = $row->invalid_temperature;
                    $correctedValue = $row->temperature;
                }

                return [
                    'date' => $row->date,
                    'time' => $row->time,
                    'fault_type' => $type,
                    'details' => $details,
                    'corrected_value' => $correctedValue,
                ];
            });

        $pastHour = [
            'total_measurements' => $totalMeasurements,
            'faults' => $totalFaults,
            'faultCountBy5Minutes' => [
                'label' => 'Faults',
                'data' => $filled,
                'borderColor' => 'rgba(59, 130, 246, 1)',
                'backgroundColor' => 'rgba(59, 130, 246, 0.15)',
                'fill' => true,
            ],
        ];

        return view('admin.faults.station', compact('station', 'pastHour', 'faultTypeBreakdown', 'recentFaults'));
    }
}
