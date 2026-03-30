<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\Subscription;
use App\Models\SubscriptionStation;
use Illuminate\Http\Request;

class SubscriptionStationController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::orderBy('identifier')->paginate(10);
        return view('admin.subscriptionStations.index', compact('subscriptions'));
    }

    public function show(Subscription $subscription)
    {
        $subscriptionStations = SubscriptionStation::with('getStation')
            ->where('subscription', $subscription->id)
            ->get();

        $linkedStationNames = $subscriptionStations->pluck('station')->toArray();
        $stations = Station::orderBy('name')->whereNotIn('name', $linkedStationNames)->get();

        return view('admin.subscriptionStations.show', compact('subscription', 'subscriptionStations', 'stations'));
    }

    public function store(Request $request, Subscription $subscription)
    {
        $request->validate([
            'station_id'    => 'required|array',
            'station_id.*'  => 'exists:station,name',
        ]);

        foreach ($request->station_id as $stationName) {
            SubscriptionStation::firstOrCreate([
                'station'      => $stationName,
                'subscription' => $subscription->id,
            ]);
        }

        return redirect()->route('admin.subscriptionStations.show', $subscription)
            ->with('success', count($request->station_id) . ' station(s) gekoppeld.');
    }

    public function destroy($station, $subscription)
    {
        SubscriptionStation::where('station', $station)
            ->where('subscription', $subscription)
            ->delete();

        return redirect()->route('admin.subscriptionStations.show', $subscription)
            ->with('success', 'Koppeling verwijderd.');
    }
}