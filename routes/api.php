<?php

use App\Http\Controllers\Auth\AuthenticateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthenticateController::class, 'requestOtp'])->name('otp.request');
Route::post('login/confirm', [AuthenticateController::class, 'confirmOtp'])->name('otp.confirm');
