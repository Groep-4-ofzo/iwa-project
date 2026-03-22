<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    public function index() {
        // Get all data as JSON
        return response()->json(Measurement::all());
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
