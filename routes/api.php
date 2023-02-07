<?php

use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//otp
Route::post('login', [AuthenticateController::class, 'requestOtp'])->name('otp.request');
Route::post('login/confirm', [AuthenticateController::class, 'confirmOtp'])->name('otp.confirm');

//warehouse
Route::apiResource('warehouses' , WarehouseController::class)->names('warehouses');
