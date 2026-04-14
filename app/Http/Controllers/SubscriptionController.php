<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Subscription;
use App\Models\SubscriptionType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with('getCompany')->paginate(10);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $companies = Company::all();
        $subscriptionTypes = SubscriptionType::all();

        return view('admin.subscriptions.create', compact('companies', 'subscriptionTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'identifier' => 'required|string|max:45|unique:subscriptions',
            'company' => 'required|exists:companies,id',
            'type' => 'required|exists:subscription_types,id',
        ]);

        Subscription::create([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'price' => $request->price,
            'notes' => $request->notes,
            'identifier' => $request->identifier,
            'token' => Str::random(32),
            'company' => $request->company,
            'type' => $request->type,
        ]);

        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription created successfully.');
    }

    public function edit(Subscription $subscription)
    {
        $companies = Company::all();
        $subscriptionTypes = SubscriptionType::all();

        return view('admin.subscriptions.edit', compact('subscription', 'companies', 'subscriptionTypes'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'identifier' => 'required|string|max:45|unique:subscriptions,identifier,'.$subscription->id,
            'company' => 'required|exists:companies,id',
            'type' => 'required|exists:subscription_types,id',
        ]);

        $subscription->start_date = $request->start_date;
        $subscription->end_date = $request->end_date;
        $subscription->price = $request->price;
        $subscription->notes = $request->notes;
        $subscription->identifier = $request->identifier;
        $subscription->company = $request->company;
        $subscription->type = $request->type;

        $subscription->save();

        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription updated successfully.');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription deleted successfully.');
    }
}
