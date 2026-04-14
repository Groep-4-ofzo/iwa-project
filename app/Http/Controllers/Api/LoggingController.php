<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EndpointActivity;
use App\Models\UserActivity;

class LoggingController extends Controller
{
    public function activity()
    {
        return response()->json([
            'endpoint' => EndpointActivity::orderBy('activity_date', 'desc')->orderBy('activity_time', 'desc')->get(),
            'user' => UserActivity::orderBy('activity_date', 'desc')->orderBy('activity_time', 'desc')->get(),
        ]);
    }
}
