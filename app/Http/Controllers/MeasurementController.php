<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use App\Models\Station;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    public function index(Request $request)
{
    $stations = Station::orderBy('name')->get();
    $query = Measurement::with('getStation');

    
    if ($request->filled('station')) {
        $query->where('station', $request->station)->first();
    }

    // Filter op datum
    if ($request->filled('date_from')) {
        $query->whereDate('date', '>=', $request->date_from);
    }
    if ($request->filled('date_to')) {
        $query->whereDate('date', '<=', $request->date_to);
    }

    if ($request->filled('time_from')) {
        $query->whereTime('time', '>=', $request->time_from);
    }
    if ($request->filled('time_to')) {
        $query->whereTime('time', '<=', $request->time_to);
    }

    if ($request->filled('temp_min')) {
        $query->where('temperature', '>=', $request->temp_min);
    }
    if ($request->filled('temp_max')) {
        $query->where('temperature', '<=', $request->temp_max);
    }

    if ($request->filled('conditions')) {
        $query->where('conditions', 'like', '%' . $request->conditions . '%');
    }

    $sort = $request->get('sort', 'date');
    $dir = $request->get('dir', 'desc');

    $measurements = $query->orderBy($sort, $dir)
        ->orderBy('time', $dir)
        ->paginate(15)
        ->withQueryString();

    return view('admin.measurements.index', compact('stations', 'measurements'));
}
}
