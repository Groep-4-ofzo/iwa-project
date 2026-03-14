<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\CompareController;


Route::get('/test', [TestController::class, 'test'])->name('test');

Route::get('/compare', [CompareController::class, 'index'])->name('compare');
Route::post('/compare', [CompareController::class, 'compare']);


Route::get('/', function () {
    return view('index');
});
