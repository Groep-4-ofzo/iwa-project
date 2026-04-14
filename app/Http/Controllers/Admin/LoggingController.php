<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EndpointActivity;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserActivity;

class LoggingController extends Controller
{
    public function overview()
    {
        return view('admin.logging.overview');
    }

    public function users(User $user)
    {
        $userActivity = UserActivity::all()->where('userid', $user->id);

        return view('admin.logging.user', compact('user', 'userActivity'));
    }

    public function subscriptions(Subscription $subscription)
    {
        $endpointActivity = EndpointActivity::all()->where('identifier', $subscription->identifier);

        return view('admin.logging.subscription', compact('subscription', 'endpointActivity'));
    }
}
