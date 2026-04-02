<?php

use App\Http\Controllers\Admin\LoggingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FaultController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubscriptionTypeController;
use App\Http\Controllers\SubscriptionStationController;


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
    Route::get('/logs', [LoggingController::class, 'overview'])->name('admin.logs.overview');
    Route::get('/logs/user/{user}', [LoggingController::class, 'users'])->name('admin.logs.user');
    Route::get('/logs/subscription/{subscription}', [LoggingController::class, 'subscriptions'])->name('admin.logs.subscription');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/faults', [FaultController::class, 'index'])->name('admin.faults.index');
});

Route::middleware(['auth', 'role:Commercieel medewerker,Administrator'])->prefix('admin')->group(function() {
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('admin.subscriptions.index');
    Route::get('/subscriptions/create', [SubscriptionController::class, 'create'])->name('admin.subscriptions.create');
    Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('admin.subscriptions.store');
    Route::get('/subscriptions/{subscription}/edit', [SubscriptionController::class, 'edit'])->name('admin.subscriptions.edit');
    Route::put('/subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('admin.subscriptions.update');
    Route::delete('/subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('admin.subscriptions.destroy');
});


Route::middleware(['auth', 'role:Commercieel medewerker,Administrator'])->prefix('admin')->group(function() {
    Route::get('/subscriptionTypes', [SubscriptionTypeController::class, 'index'])->name('admin.subscriptionTypes.index');
    Route::get('/subscriptionTypes/create', [SubscriptionTypeController::class, 'create'])->name('admin.subscriptionTypes.create');
    Route::post('/subscriptionTypes', [SubscriptionTypeController::class, 'store'])->name('admin.subscriptionTypes.store');
    Route::get('/subscriptionTypes/{subscriptionType}/edit', [SubscriptionTypeController::class, 'edit'])->name('admin.subscriptionTypes.edit');
    Route::put('/subscriptionTypes/{subscriptionType}', [SubscriptionTypeController::class, 'update'])->name('admin.subscriptionTypes.update');
    Route::delete('/subscriptionTypes/{subscriptionType}', [SubscriptionTypeController::class, 'destroy'])->name('admin.subscriptionTypes.destroy');
});

Route::middleware(['auth', 'role:Commercieel medewerker,Administrator'])->prefix('admin')->group(function() {
    Route::get('/subscriptionStations', [SubscriptionStationController::class, 'index'])->name('admin.subscriptionStations.index');
    Route::get('/subscriptionStations/{subscription}', [SubscriptionStationController::class, 'show'])->name('admin.subscriptionStations.show');
    Route::post('/subscriptionStations/{subscription}', [SubscriptionStationController::class, 'store'])->name('admin.subscriptionStations.store');
    Route::delete('/subscriptionStations/{station}/{subscription}', [SubscriptionStationController::class, 'destroy'])->name('admin.subscriptionStations.destroy');
});
