<?php

use App\Http\Controllers\MeasurementController;
use Illuminate\Support\Facades\Route;


Route::middleware(['api.auth', 'api.log'])->group(function () {
    Route::get("/measurement", [MeasurementController::class, "index"]);

    Route::post("/measurement", [MeasurementController::class, "store"]);
});
