<?php

namespace Tests\Feature\Models;

use App\Models\Otp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_insert_data_to_database()
    {
        $data = Otp::factory()->make()->toArray();

        Otp::create($data);

        $this->assertDatabaseCount('otps', 1);
        $this->assertDatabaseHas('otps', $data);
    }


    public function test_new_instance_of_class_otp()
    {
        $otp = Otp::new();

        $this->assertInstanceOf(Otp::class, $otp);
    }


    public function test_is_code_expired_after_expiration_end()
    {
        $expirationPeriod = config('otp.expiration_time');
        $travelPeriod = $expirationPeriod + 10;

        $this->assertDatabaseCount('otps', 0);

        $phone_number = '09121001010';
        $otp = Otp::new()->requestOtp($phone_number);
        $data = ['phone_number' => $otp->phone_number, 'code' => $otp->code];

        $this->assertDatabaseCount('otps', 1);
        $this->assertDatabaseHas('otps', $data);

        $this->travel($travelPeriod)->seconds();

        $newOtp = Otp::new()->requestOtp($phone_number);
        $newData = ['phone_number' => $newOtp->phone_number, 'code' => $newOtp->code];

        $this->assertDatabaseCount('otps', 1);
        $this->assertDatabaseHas('otps', $newData);
        $this->assertDatabaseMissing('otps', $data);
    }

    public function test_is_code_not_expired_before_expiration_end()
    {
        $expirationPeriod = config('otp.expiration_time');
        $travelPeriod = $expirationPeriod - 10;

        $this->assertDatabaseCount('otps', 0);

        $phone_number = '09121001010';
        $otp = Otp::new()->requestOtp($phone_number);
        $data = ['phone_number' => $otp->phone_number, 'code' => $otp->code];

        $this->assertDatabaseCount('otps', 1);
        $this->assertDatabaseHas('otps', $data);

        $this->travel($travelPeriod)->seconds();

        $newOtp = Otp::new()->requestOtp($phone_number);
        $newData = ['phone_number' => $newOtp->phone_number, 'code' => $newOtp->code];

        $this->assertEquals($data, $newData);
        $this->assertDatabaseCount('otps', 1);
        $this->assertDatabaseHas('otps', $data);
    }

}
