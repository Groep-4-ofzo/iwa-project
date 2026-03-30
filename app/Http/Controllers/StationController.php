<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\Geolocation;

class StationController extends Controller
{
    public function index(string $name)
    {
        $data = Station::where("name", $name)->first();

        $geo = Geolocation::where("station_name", $name)->first();

        return view("station", compact("data", "geo"));
    }
}
