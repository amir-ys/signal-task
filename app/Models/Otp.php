<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $otp;
    protected $phone_number;

    protected $guarded = [];

    public static function new(): static
    {
        return new static();
    }

    public function requestOtp(string $phone_number)
    {
        $this->phone_number = $phone_number;

        $this->processOtpRequest();

        return $this->otp;
    }

    private function processOtpRequest(): void
    {
        $otp = $this->where('phone_number', $this->phone_number)->first();

        if ($otp) {
            $this->checkCodeExpiration($otp);
        } else {
            $this->generateCode();
        }
    }

    private function checkCodeExpiration(self $otp)
    {
        $expiredAt = $otp->created_at->timestamp + config('otp.expiration_time');

        if ($expiredAt < now()->timestamp) {
            $this->generateCode(true);
        } else {
            $this->otp = $otp;
        }
    }

    private function generateCode(bool $withDelete = false)
    {
        if ($withDelete) {
            $this->where('phone_number', $this->phone_number)->delete();
        }

        $randomCode = $this->generateRandomUniqueCode();

        $this->otp = self::create([
            'phone_number' => $this->phone_number,
            'code' => $randomCode,
        ]);

    }

    private function generateRandomUniqueCode(): int
    {
        $randomCode = random_int(100000, 999999);

        $otp = $this->where('code', $randomCode)->first();
        if ($otp) {
            $this->generateRandomUniqueCode();
        }
        return $randomCode;
    }

}
