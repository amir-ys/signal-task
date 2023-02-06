<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmOtpRequest;
use App\Http\Requests\OtpRequest;
use App\Models\Otp;
use App\Models\User;
use App\Responses\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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

    public function confirmOtp(ConfirmOtpRequest $request)
    {
        $otp = Otp::query()->where('phone_number', $request->phone_number)->first();
        if (!$otp || $otp->code != $request->code) {
            return Response::error('the entered code is not valid');
        }

        $user = $this->createNewUser($request->toArray());
        $token = $user->createToken('bearer')->plainTextToken;

        return Response::data('token created successfully', [
            'token' => $token,
        ]);

    }

    private function createNewUser($data): Model|Builder
    {
        return User::query()->create([
            'phone_number' => $data['phone_number']
        ]);
    }
}
