<?php

use App\Http\Controllers\Api\MeasurementController;
use App\Http\Controllers\Api\StationController;
use Illuminate\Support\Facades\Route;


Route::middleware(['api.auth', 'api.log'])->group(function () {
    Route::get("/measurement", [MeasurementController::class, "index"]);

    Route::post("/measurement", [MeasurementController::class, "store"]);

    Route::get('/station/{station}', [StationController::class, "show"]);
});
