<?php

use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;


//otp
Route::post('login', [AuthenticateController::class, 'requestOtp'])->name('otp.request');
Route::post('login/confirm', [AuthenticateController::class, 'confirmOtp'])->name('otp.confirm');

//warehouse
Route::middleware('auth:sanctum')->group(function () {
    Route::get('warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');

    Route::apiResource('warehouses', WarehouseController::class)->names('warehouses')
        ->middleware('admin')->except('index');
});
