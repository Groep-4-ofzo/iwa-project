<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Query;
use App\Models\Measurement;
use Carbon\Carbon;

class WeatherController extends Controller
{
    public function getWeather(Request $request)
    {
        $subscription = $request->attributes->get('subscription');

        if (!$subscription) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $type = $subscription->subscriptionType;
        $contract = $subscription->contract;

        if (!$contract) {
            return response()->json(['error' => 'No contract found'], 404);
        }

        $query = Query::firstOrCreate(
            ['contract_id' => $contract->id],
            ['omschrijving' => 'Weather API pull']
        );

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

        $stations = $subscription->stations->pluck('name')->toArray();

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
