<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EndpointActivity;
use App\Models\UserActivity;
use Illuminate\Http\Request;

class LoggingController extends Controller
{
    public function activity()
    {
        return response()->json([
            'endpoint' => EndpointActivity::all(),
            'user' => UserActivity::all(),
        ]);
    }
}
