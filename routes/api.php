<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\DelayController;


Route::apiResource('sites', SiteController::class);
Route::post('sites/{site}/weather', [WeatherController::class, 'store']);
Route::post('sites/{site}/delays', [DelayController::class, 'store']);
Route::get('sites/{site}/correlation', [DelayController::class, 'correlation']);