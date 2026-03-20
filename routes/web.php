<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\UserController;


Route::get("/compare", [CompareController::class, "index"])->name("compare");
Route::post("/compare", [CompareController::class, "compare"]);

Route::get("/station/{name}", [StationController::class, "index"])->name("station");

Route::get("/", function () {
    return view("index");
});

Route::controller(LoginRegisterController::class)->group(function () {

    Route::middleware(['guest'])->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/authenticate', 'authenticate')->name('authenticate');
        Route::post('/store', 'store')->name('store');
    });

    Route::middleware(['auth'])->group(function () {

        Route::get('/admin/dashboard', 'dashboard')->name('admin.dashboard');
        Route::post('/logout', 'logout')->name('logout');

    });

});

Route::middleware(['auth', 'role:Administrator'])->prefix('admin')->group(function() {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});
