<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use App\Services\MeasurementIngestService;
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

        $ingestService = new MeasurementIngestService();

        foreach ($validated["WEATHERDATA"] as $item) {
            $sanitizedItem = array_map(fn($value) => $value === "None" ? null : $value, $item);

            $ingestService->handleOne($sanitizedItem);
        }

        return response()->json(["status" => "success"], 201);
    }
}
