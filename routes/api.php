<?php

use App\Http\Controllers\Api\LoggingController;
use App\Http\Controllers\Api\MeasurementController;
use App\Http\Controllers\Api\StationController;
use App\Http\Controllers\Api\GeneratorController;
use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\AppUserController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;


Route::middleware(['api.auth', 'api.log'])->group(function () {
    Route::get("/measurement", [MeasurementController::class, "index"]);
    Route::get('/station/{station}', [StationController::class, "show"]);
    Route::get('/weather', [WeatherController::class, 'getWeather']);
});

Route::middleware(['api.auth', 'api.log'])->group(function () {
    Route::get("/contract/{identifier}/users", [AppUserController::class, "index"]);
    Route::post("/contract/{identifier}/users", [AppUserController::class, "store"]);
    Route::get("/contract/{identifier}/users/{user_identifier}", [AppUserController::class, "show"]);
    Route::delete("/contract/{identifier}/users/{user_identifier}", [AppUserController::class, "delete"]);
    Route::put("/contract/{identifier}/users/{user-identifier}", [AppUserController::class, "update"]);
});

Route::middleware(['web', 'auth', 'role:Administrator'])->group(function () { // nog even vragen aan riemer
    Route::get("/logs", [LoggingController::class, "activity"]);
});

Route::middleware(["api.gen"])->group(function () {
    Route::post("/measurement", [GeneratorController::class, "store"]);
});
