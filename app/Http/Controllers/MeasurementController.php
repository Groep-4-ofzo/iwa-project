<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    public function index()
    {
        // Get all data as JSON
        return response()->json(Measurement::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "WEATHERDATA" => "required|array",

            "WEATHERDATA.*.STN" => "required",
            "WEATHERDATA.*.DATE" => "required",
            "WEATHERDATA.*.TIME" => "required",
            "WEATHERDATA.*.TEMP" => "required",
            "WEATHERDATA.*.DEWP" => "required",
            "WEATHERDATA.*.STP" => "required",
            "WEATHERDATA.*.SLP" => "required",
            "WEATHERDATA.*.VISIB" => "required",
            "WEATHERDATA.*.WDSP" => "required",
            "WEATHERDATA.*.PRCP" => "required",
            "WEATHERDATA.*.SNDP" => "required",
            "WEATHERDATA.*.FRSHTT" => "required",
            "WEATHERDATA.*.CLDC" => "required",
            "WEATHERDATA.*.WNDDIR" => "required",
        ]);

        foreach ($validated["WEATHERDATA"] as $item) {
            $sanitizedItem = array_map(fn($value) => $value === "None" ? null : $value, $item);

            Measurement::create([
                "station" => $sanitizedItem["STN"],
                "date" => $sanitizedItem["DATE"],
                "time" => $sanitizedItem["TIME"],
                "temperature" => $sanitizedItem["TEMP"],
                "dewpoint_temperature" => $sanitizedItem["DEWP"],
                "air_pressure_station" => $sanitizedItem["STP"],
                "air_pressure_sea_level" => $sanitizedItem["SLP"],
                "visibility" => $sanitizedItem["VISIB"],
                "wind_speed" => $sanitizedItem["WDSP"],
                "percipation" => $sanitizedItem["PRCP"],
                "snow_depth" => $sanitizedItem["SNDP"],
                "conditions" => $sanitizedItem["FRSHTT"],
                "cloud_cover" => $sanitizedItem["CLDC"],
                "wind_direction" => $sanitizedItem["WNDDIR"],
            ]);
        }

        return response()->json(["status" => "success"], 201);
    }
}
