<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionType;
use Illuminate\Http\Request;

class SubscriptionTypeController extends Controller
{
    public function index()
    {
        $subscriptionTypes = SubscriptionType::paginate(10);
        return view('admin.subscriptionTypes.index', compact('subscriptionTypes'));
    }

    public function create()
    {
        return view('admin.subscriptionTypes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'               => 'required|string|max:45',
            'description'        => 'nullable|string|max:256',
            'nr_stations'        => 'nullable|integer|min:1',
            'frequency_in_hours' => 'nullable|integer|min:0',
            'frequency_in_days'  => 'nullable|integer|min:0',
            'continuous'         => 'nullable|boolean',
            'price_per_station'  => 'required|numeric|min:0',
            'valid_through'      => 'nullable|date',
        ]);

        SubscriptionType::create([
            'name'               => $request->name,
            'description'        => $request->description,
            'nr_stations'        => $request->nr_stations,
            'frequency_in_hours' => $request->frequency_in_hours,
            'frequency_in_days'  => $request->frequency_in_days,
            'continuous'         => $request->boolean('continuous'),
            'price_per_station'  => $request->price_per_station,
            'valid_through'      => $request->valid_through,
        ]);

        return redirect()->route('admin.subscriptionTypes.index')
            ->with('success', 'Subscription type created successfully.');
    }

    public function edit(SubscriptionType $subscriptionType)
    {
        return view('admin.subscriptionTypes.edit', compact('subscriptionType'));
    }

    public function update(Request $request, SubscriptionType $subscriptionType)
    {
        $request->validate([
            'name'               => 'required|string|max:45',
            'description'        => 'nullable|string|max:256',
            'nr_stations'        => 'nullable|integer|min:1',
            'frequency_in_hours' => 'nullable|integer|min:0',
            'frequency_in_days'  => 'nullable|integer|min:0',
            'continuous'         => 'nullable|boolean',
            'price_per_station'  => 'required|numeric|min:0',
            'valid_through'      => 'nullable|date',
        ]);

        $subscriptionType->name               = $request->name;
        $subscriptionType->description        = $request->description;
        $subscriptionType->nr_stations        = $request->nr_stations;
        $subscriptionType->frequency_in_hours = $request->frequency_in_hours;
        $subscriptionType->frequency_in_days  = $request->frequency_in_days;
        $subscriptionType->continuous         = $request->boolean('continuous');
        $subscriptionType->price_per_station  = $request->price_per_station;
        $subscriptionType->valid_through      = $request->valid_through;

        $subscriptionType->save();

        return redirect()->route('admin.subscriptionTypes.index')
            ->with('success', 'Subscription type updated successfully.');
    }

    public function destroy(SubscriptionType $subscriptionType)
    {
        $subscriptionType->delete();
        return redirect()->route('admin.subscriptionTypes.index')
            ->with('success', 'Subscription type deleted successfully.');
    }
}