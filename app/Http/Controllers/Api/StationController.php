<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function show($station) {
        $data = Station::with(['geolocation.country', 'nearestlocation.country'])
            ->findOrFail($station);

        return response()->json($data);
    }
}
