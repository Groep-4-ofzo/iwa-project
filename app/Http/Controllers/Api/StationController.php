<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Station;
use Illuminate\Http\Request;
use App\Models\Nearestlocation;
use Illuminate\Support\Facades\DB;


class StationController extends Controller
{
    public function show(Request $request)
    {
        $stationName = $request->route('name');
        $contractId = $request->route('identifier');

        $data = Station::with('nearestlocation.country')
            ->where('name', $stationName)
            ->first();
        
        $nearestLocation = $data->nearestlocation[0] ?? null;

        $dataOfAdminRegion1 = Nearestlocation::where('administrative_region1', $nearestLocation->administrative_region1)->get();
        $dataOfAdminRegion2 = Nearestlocation::where('administrative_region2', $nearestLocation->administrative_region2)->get();
        $response = [
            'station' => $data,
            'same_admin_region1' => $dataOfAdminRegion1,
            'same_admin_region2' => $dataOfAdminRegion2,
        ];
        return response()->json($response);
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
