<?php

use App\Http\Controllers\Api\MeasurementController;
use App\Http\Controllers\Api\StationController;
use App\Http\Controllers\Api\GeneratorController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;


Route::middleware(['api.auth', 'api.log'])->group(function () {
    Route::get("/measurement", [MeasurementController::class, "index"]);
    Route::get('/station/{station}', [StationController::class, "show"]);
});

Route::middleware(["api.gen"])->group(function () {
    Route::post("/measurement", [GeneratorController::class, "store"]);
});
