<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\Measurement;
use Illuminate\Http\Request;
use App\Models\Geolocation;

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

        //gebruik wanneer data in measurement table
//        $data1 = Measurement::where('station', $station1)
//            ->orderBy('date', 'desc')
//            ->orderBy('time', 'desc')
//            ->take(10)
//            ->get();
//
//        $data2 = Measurement::where('station', $station2)
//            ->orderBy('date', 'desc')
//            ->orderBy('time', 'desc')
//            ->take(10)
//            ->get();

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
