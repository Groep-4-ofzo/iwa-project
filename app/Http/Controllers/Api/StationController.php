<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class StationController extends Controller
{
    public function show($station)
    {
        $data = Station::with(['geolocation.country', 'nearestlocation.country'])
            ->findOrFail($station);

        return response()->json($data);
    }

    public function stationsByNearestLocation(Request $request)
    {
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $stations = DB::table('station as s')
            ->join('nearestlocation as n', 's.name', '=', 'n.station_name')
            ->selectRaw("
        s.name as station_name,
        s.latitude,
        s.longitude,
        n.name as nearest_name,
        ST_Distance_Sphere(
            POINT(s.longitude, s.latitude),
            POINT(?, ?)
        ) AS distance_meters
    ", [$longitude, $latitude])
            ->orderBy('distance_meters')
            ->limit(1)
            ->get();
        return response()->json($stations);
    }
}
