<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use App\Models\Query;
use App\Models\Station;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Services\QueryRecordFilterService;

class MeasurementController extends Controller
{
    public function index(Request $request)
    {
        $stationName = $request->route('name');
        $contractId = $request->route('identifier');

        $queryRecord = Query::with('groups.criteria.type')
            ->where('contract_id', $contractId)
            ->first();

        $sql = Station::query();

        $filterService = new QueryRecordFilterService();

        $sql = $filterService->apply($sql, $queryRecord);

        $sql = $sql->where('name', $stationName);

        if (!$sql->exists()) {
            return response()->json(['error' => 'Station not found or not accessible']);
        } 
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

      
        $query->where('station', $stationName);
        

        return response()->json($query->limit(1000)->get());
    }
}
