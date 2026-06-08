<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Station;
use Illuminate\Http\Request;
use App\Models\Nearestlocation;
use App\Models\Query;
use Illuminate\Support\Facades\DB;
use App\Services\QueryRecordFilterService;

class StationController extends Controller
{
    public function show(Request $request)
    {
        $stationName = $request->route("name");
        $contractId = $request->route("identifier");

        $queryRecord = Query::with("groups.criteria.type")->where("contract_id", $contractId)->first();

        $sql = Station::query();

        $filterService = new QueryRecordFilterService();

        $sql = $filterService->apply($sql, $queryRecord);

        $data = $sql->with("nearestlocation.country")->where("name", $stationName)->first();

        $nearestLocation = $data->nearestlocation[0] ?? null;
        if (!$nearestLocation) {
            return response()->json($data);
        }
        $dataOfAdminRegion1 = Nearestlocation::where("administrative_region1", $nearestLocation->administrative_region1)->get();
        $dataOfAdminRegion2 = Nearestlocation::where("administrative_region2", $nearestLocation->administrative_region2)->get();
        $response = [
            "station" => $data,
            "same_admin_region1" => $dataOfAdminRegion1,
            "same_admin_region2" => $dataOfAdminRegion2,
        ];

        return response()->json($response);
    }

    public function stationsByNearestLocation(Request $request)
    {
        return response()->json([
            "message" => "hallo",
        ]);

        //     $contractId = $request->route("identifier");
        //     $request->validate([
        //         "latitude" => "required|numeric",
        //         "longitude" => "required|numeric",
        //     ]);

        //     $latitude = $request->latitude;
        //     $longitude = $request->longitude;

        //     $queryRecord = Query::with("groups.criteria.type")->where("contract_id", $contractId)->first();

        //     $nearestStations = Station::query()
        //         ->join("nearestlocation as n", "station.name", "=", "n.station_name")
        //         ->selectRaw(
        //             "
        //     station.name as station_name,
        //     station.latitude,
        //     station.longitude,
        //     n.name as nearest_name,
        //     n.country_code as country_code,
        //     ST_Distance_Sphere(
        //         POINT(station.longitude, station.latitude),
        //         POINT(?, ?)
        //     ) AS distance_meters
        // ",
        //             [$longitude, $latitude],
        //         )
        //         ->orderBy("distance_meters");

        //     $firstStation = $nearestStations->first();

        //     $stationQuery = Station::query();

        //     $filterService = new QueryRecordFilterService();

        //     $checkQuery = $filterService->apply($stationQuery, $queryRecord);

        //     $grantedStations = $checkQuery->get()->toArray();

        //     if (in_array($firstStation->station_name, array_column($grantedStations, "name"))) {
        //         $station = $firstStation;
        //     } else {
        //         $station = null;
        //     }

        //     return response()->json($station);
    }
}
