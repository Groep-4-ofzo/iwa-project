<?php

namespace App\Http\Controllers;

use App\Models\Geolocation;
use App\Models\Station;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function index()
    {
        $stations = Station::all();

        return view('compare', compact('stations'));
    }

    public function compare(Request $request)
    {
        $stations = Station::all();

        $station1 = $request->station1;
        $station2 = $request->station2;

        $data1 = Station::where('name', $station1)->first();
        $data2 = Station::where('name', $station2)->first();

        $geo1 = Geolocation::where('station_name', $station1)->first();
        $geo2 = Geolocation::where('station_name', $station2)->first();

        return view('compare', compact(
            'stations',
            'station1',
            'station2',
            'data1',
            'data2',
            'geo1',
            'geo2'
        ));
    }
}
