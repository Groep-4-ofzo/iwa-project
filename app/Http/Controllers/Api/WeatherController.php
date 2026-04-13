<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Query;
use App\Models\Measurement;
use App\Models\Station;
use Carbon\Carbon;

class WeatherController extends Controller
{
    public function getWeather(Request $request)
    {
        $subscription = $request->attributes->get('subscription');
        $query_id = $request->query_id;
        if (!$subscription) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $type = $subscription->subscriptionType;
        $contract = $subscription->contract;

        if (!$contract) {
            return response()->json(['error' => 'No contract found'], 404);
        }
        $query = Query::where([
            ['contract_id', $contract->id],
            ['id', (int) $query_id]
        ])->first();


        if (!$query) {
            return response()->json(['error' => 'No query found'], 404);
        }



        $now = Carbon::now();
        $lastPull = $query->last_valid_pull;

        $canPullNewData = false;

        if (!$lastPull) {
            $canPullNewData = true;
        } else {
            if ($type->frequency_in_hours) {
                $next = Carbon::parse($lastPull)->addHours($type->frequency_in_hours);
                if ($now->gte($next)) {
                    $canPullNewData = true;
                }
            }

            if ($type->frequency_in_days) {
                $next = Carbon::parse($lastPull)->addDays($type->frequency_in_days);
                if ($now->gte($next)) {
                    $canPullNewData = true;
                }
            }
        }

        if (!$canPullNewData) {
            return response()->json([
                'new_data' => false,
                'message' => 'No new data available yet',
                'last_pull' => $lastPull
            ]);
        }

        $queryRecord = Query::with('groups.criteria.type')->findOrFail($query->id);


        $sql = Station::query();

        foreach ($queryRecord->groups as $group) {
            $method = ($group->operator == 1) ? 'where' : 'orWhere';

            $sql->$method(function ($sub) use ($group) {
                foreach ($group->criteria as $c) {

                    $column = $c->type->referenced_field;
                    $table = $c->type->referenced_table;

                    $val = $c->int_value ?? $c->string_value;

                    $operator = [
                        1 => '=',
                        2 => '<',
                        3 => '<=',
                        4 => '>',
                        5 => '>=',
                        6 => '!=',
                    ][$c->value_comparison] ?? '=';

                    if ($column === 'country_code' || $column === 'country') {

                        $sub->whereHas('geolocation', function ($geo) use ($column, $operator, $val) {
                            $geo->where($column, $operator, $val);
                        });

                    } else {

                        $sub->where($column, $operator, $val);
                    }
                }
            });
        }
        $stations = $sql
            ->with('geolocation') 
            ->distinct()
            ->get()
            ->pluck('name');

        if (empty($stations)) {
            return response()->json([
                'new_data' => false,
                'message' => 'No stations linked to this subscription'
            ]);
        }

        $data = Measurement::whereIn('station', $stations)
            ->when($lastPull, function ($query) use ($lastPull) {
                $date = Carbon::parse($lastPull)->toDateString();
                $time = Carbon::parse($lastPull)->toTimeString();

                $query->where(function ($q) use ($date, $time) {
                    $q->where('date', '>', $date)
                        ->orWhere(function ($q2) use ($date, $time) {
                            $q2->where('date', '=', $date)
                                ->where('time', '>', $time);
                        });
                });
            })
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        if ($data->isNotEmpty()) {
            $query->last_valid_pull = $now;
            $query->save();
        }

        return response()->json([
            'new_data' => true,
            'count' => $data->count(),
            'data' => $data
        ]);
    }
}
