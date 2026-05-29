<?php

use App\Http\Controllers\Api\LoggingController;
use App\Http\Controllers\Api\MeasurementController;
use App\Http\Controllers\Api\StationController;
use App\Http\Controllers\Api\GeneratorController;
use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\AppUserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QueryController;
use Illuminate\Support\Facades\Route;


Route::prefix('contracten')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post("/{identifier}/user", [AppUserController::class, "store"]);


    Route::middleware(['auth:api'])->group(function () {
        // Routes for JWT Authentication
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/me', [AuthController::class, 'me']);


        // Routes for /contracten/etc
        Route::get("/{identifier}/users", [AppUserController::class, "index"]);

        Route::get("/{identifier}/user/{user_identifier}", [AppUserController::class, "show"]);
        Route::delete("/{identifier}/user/{user_identifier}", [AppUserController::class, "delete"]);
        Route::put("/{identifier}/user/{user_identifier}", [AppUserController::class, "update"]);


        // Routes query
        Route::get('/{identifier}/{queryID}', [QueryController::class, 'show']);
        Route::get('/{identifier}/{queryID}/stations', [QueryController::class, 'getStations']);
    });
});

Route::middleware(['api.auth', 'api.log'])->group(function () {
    Route::get("/measurement", [MeasurementController::class, "index"]);
    Route::get('/station/{station}', [StationController::class, "show"]);

    Route::get('/weather', [WeatherController::class, 'getWeather']);
});

Route::middleware(['web', 'auth', 'role:Administrator'])->group(function () { // nog even vragen aan riemer
    Route::get("/logs", [LoggingController::class, "activity"]);
});

Route::middleware(["api.gen"])->group(function () {
    Route::post("/measurement", [GeneratorController::class, "store"]);
});

Route::get('/stations/nearest', [
    StationController::class,
    'stationsByNearestLocation'
]);
