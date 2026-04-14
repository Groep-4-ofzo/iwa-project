<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    public function index(Request $request)
    {
        $query = Measurement::query();

        if ($request->has('from')) {
            $datetime = explode(' ', $request->from);
            $date = $datetime[0];
            $time = $datetime[1] ?? '00:00:00';

            $query->where(function (Builder $query) use ($date, $time) {
                $query->where('date', '>', $date)
                    ->orWhere(function (Builder $query2) use ($date, $time) {
                        $query2->where('date', '=', $date)
                            ->where('time', '>=', $time);
                    });
            });
        }

        if ($request->has('to')) {
            $datetime = explode(' ', $request->to);
            $date = $datetime[0];
            $time = $datetime[1] ?? '23:59:59';

            $query->where(function (Builder $query) use ($date, $time) {
                $query->where('date', '<', $date)
                    ->orWhere(function (Builder $query2) use ($date, $time) {
                        $query2->where('date', '=', $date)
                            ->where('time', '<=', $time);
                    });
            });
        }

        if ($request->has('station')) {
            $query->where('station', $request->station);
        }

        return response()->json($query->limit(1000)->get());
    }
}
