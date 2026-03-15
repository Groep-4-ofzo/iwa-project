<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\StationController;


Route::get("/compare", [CompareController::class, "index"])->name("compare");
Route::post("/compare", [CompareController::class, "compare"]);

Route::get("/station/{name}", [StationController::class, "index"])->name("station");

Route::get("/", function () {
    return view("index");
});
