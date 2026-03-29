<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    public function index(Request $request) {

        $query = Measurement::query();

        if ($request->has('from')) {
            $datetime = explode(' ', $request->from);
            $date = $datetime[0];
            $time = $datetime[1] ?? '00:00:00';

            $query->where(function (Builder $query) use ($date, $time) {
                $query->where('date', '>', $date)
                    ->orWhere(function (Builder $query2) use ($date, $time) {
                        $query2->where('date', '=', $date)
                            ->where('time', '>=', $time);
                    });
            });
        }

        if ($request->has('to')) {
            $datetime = explode(' ', $request->to);
            $date = $datetime[0];
            $time = $datetime[1] ?? '23:59:59';

            $query->where(function (Builder $query) use ($date, $time) {
                $query->where('date', '<', $date)
                    ->orWhere(function (Builder $query2) use ($date, $time) {
                        $query2->where('date', '=', $date)
                            ->where('time', '<=', $time);
                    });
            });
        }

        if ($request->has('station')) {
            $query->where('station', $request->station);
        }

        return response()->json($query->get());
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'WEATHERDATA' => 'required|array',

            'WEATHERDATA.*.STN' => 'required',
            'WEATHERDATA.*.DATE' => 'required',
            'WEATHERDATA.*.TIME' => 'required',
            'WEATHERDATA.*.TEMP' => 'required',
            'WEATHERDATA.*.DEWP' => 'required',
            'WEATHERDATA.*.STP' => 'required',
            'WEATHERDATA.*.SLP' => 'required',
            'WEATHERDATA.*.VISIB' => 'required',
            'WEATHERDATA.*.WDSP' => 'required',
            'WEATHERDATA.*.PRCP' => 'required',
            'WEATHERDATA.*.SNDP' => 'required',
            'WEATHERDATA.*.FRSHTT' => 'required',
            'WEATHERDATA.*.CLDC' => 'required',
            'WEATHERDATA.*.WNDDIR' => 'required',
        ]);

        $weather_data = $validated["WEATHERDATA"];

        foreach ($weather_data as $item) {
            Measurement::create([
                'station' => $item["STN"],
                'date' => $item["DATE"],
                'time' => $item["TIME"],
                'temperature' => $item["TEMP"],
                'dewpoint_temperature' => $item["DEWP"],
                'air_pressure_station' => $item["STP"],
                'air_pressure_sea_level' => $item["SLP"],
                'visibility' => $item["VISIB"],
                'wind_speed' => $item["WDSP"],
                'percipation' => $item["PRCP"],
                'snow_depth' => $item["SNDP"],
                'conditions' => $item["FRSHTT"],
                'cloud_cover' => $item["CLDC"],
                'wind_direction' => $item["WNDDIR"],
            ]);
        }

        return response()->json([
            'status' => 'success',
        ], 201);
    }

}
