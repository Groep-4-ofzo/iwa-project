<?php

namespace App\Http\Controllers;

use App\Models\Geolocation;
use App\Models\Measurement;
use App\Models\Station;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;

class StationController extends Controller
{
    public function index(string $name): View
    {
        $station = Station::query()->where("name", $name)->firstOrFail();
        $geo = Geolocation::query()->where("station_name", $name)->first();

        $latestMeasurement = Measurement::query()->where("station", $name)->orderByDesc("date")->orderByDesc("time")->first();

        $sinceHour = Carbon::now()->subHour();
        $dtExpr = "STR_TO_DATE(CONCAT(`date`, ' ', `time`), '%Y-%m-%d %H:%i:%s')";

        $dayRows = Measurement::query()
            ->where("station", $name)
            ->whereRaw("$dtExpr >= ?", [$sinceHour->toDateTimeString()])
            ->selectRaw(
                "
                `date`,
                `time`,
                `temperature`,
                `air_pressure_station`,
                `wind_speed`,
                `percipation`
            ",
            )
            ->orderBy("date")
            ->orderBy("time")
            ->get();

        $toChart = static function ($rows): array {
            $keys = ["temp", "air_pressure_station", "wind_speed", "percipation"];
            $byKey = array_fill_keys($keys, []);

            foreach ($rows as $row) {
                $x = trim((string) $row->date . " " . (string) $row->time);

                $tempVal = $row->temperature ?? null;
                $byKey["temp"][] = [
                    "x" => $x,
                    "y" => is_null($tempVal) ? null : (float) $tempVal,
                ];

                foreach (["air_pressure_station", "wind_speed", "percipation"] as $k) {
                    $val = $row->{$k} ?? null;
                    $byKey[$k][] = [
                        "x" => $x,
                        "y" => is_null($val) ? null : (float) $val,
                    ];
                }
            }

            return [
                ["data" => $byKey["temp"], "label" => "Temperature"],
                ["data" => $byKey["air_pressure_station"], "label" => "Air Pressure"],
                ["data" => $byKey["wind_speed"], "label" => "Wind Speed"],
                ["data" => $byKey["percipation"], "label" => "Percipation"],
            ];
        };

        [$chartTemp, $chartAirPressure, $chartWindSpeed, $chartPercipation] = $toChart($dayRows);

        $stats = [
            "station" => [
                "name" => $station->name,
                "latitude" => $station->latitude,
                "longitude" => $station->longitude,
                "elevation" => $station->elevation,
            ],
            "latest" => $latestMeasurement
                ? [
                    "date" => $latestMeasurement->date,
                    "time" => $latestMeasurement->time,
                    "temperature" => $latestMeasurement->temperature,
                    "dewpoint_temperature" => $latestMeasurement->dewpoint_temperature,
                    "air_pressure_station" => $latestMeasurement->air_pressure_station,
                    "air_pressure_sea_level" => $latestMeasurement->air_pressure_sea_level,
                    "visibility" => $latestMeasurement->visibility,
                    "wind_speed" => $latestMeasurement->wind_speed,
                    "wind_direction" => $latestMeasurement->wind_direction,
                    "percipation" => $latestMeasurement->percipation,
                    "snow_depth" => $latestMeasurement->snow_depth,
                    "conditions" => $latestMeasurement->conditions,
                    "cloud_cover" => $latestMeasurement->cloud_cover,
                ]
                : null,
        ];

        return view("station", compact("station", "geo", "latestMeasurement", "stats", "chartTemp", "chartAirPressure", "chartWindSpeed", "chartPercipation"));
    }

    public function list()
    {
        $search = request('search');

        $stations = Station::when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })
        ->paginate(13)
        ->withQueryString();

        return view('stations.index', compact('stations'));
    }
}
