<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;


Route::get('/test', [TestController::class, 'test'])->name('test');


Route::get('/', function () {
    return view('index');
});