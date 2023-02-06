<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\OtpRequest;
use App\Models\Otp;
use App\Responses\Response;
use Illuminate\Support\Facades\Log;

class AuthenticateController extends Controller
{
    public function requestOtp(OtpRequest $request)
    {
        $phone_number = $request->phone_number;

        $otp = Otp::new()->requestOtp($phone_number);

        Log::alert("send otp : $otp->code to user");

        return Response::succes('a one time code has been generated and send to you');
    }
}
