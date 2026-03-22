<?php

use App\Http\Controllers\Generator\GeneratorController;

Route::post("/gen/data/insert", GeneratorController::class->name('insert'));
