<?php

namespace Tests\Feature\Controllers;

use App\Models\Otp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthenticateControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_otp()
    {
        $response = $this->postJson(route('otp.request'), ['phone_number' => '09121001010']);

        $this->assertDatabaseCount('otps', 1);
        $this->assertDatabaseHas('otps', ['phone_number' => '09121001010']);

        $response->assertJson(['message' => 'a one time code has been generated and send to you']);
        $response->assertOk();
    }

    public function test_validation_request_for_request_otp()
    {
        $response = $this->postJson(route('otp.request'), []);
        $response->assertJsonValidationErrors([
            'phone_number' => trans('validation.required', ['attribute' => 'phone number'])
        ]);

        $response = $this->postJson(route('otp.request'), ['phone_number' => Str::random(5)]);
        $response->assertJsonValidationErrors([
            'phone_number' => [
                trans('validation.numeric', ['attribute' => 'phone number']),
                trans('validation.digits', ['attribute' => 'phone number', 'digits' => 11]),
            ]
        ]);
    }

    public function test_confirm_otp()
    {
        $data = Otp::factory()->create()->toArray();
        $response = $this->postJson(route('otp.confirm'), $data);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', ['phone_number' => $data['phone_number']]);

        $response->assertJson(['message' => 'token created successfully']);
        $response->assertOk();
    }

    public function test_validation_request_for_confirm_otp()
    {
        $response = $this->postJson(route('otp.confirm'), []);
        $response->assertJsonValidationErrors([
            'phone_number' => trans('validation.required', ['attribute' => 'phone number']),
            'code' => trans('validation.required', ['attribute' => 'code']),
        ]);

        $data = ['phone_number' => Str::random(5), 'code' => Str::random(5)];
        $response = $this->postJson(route('otp.confirm'), $data);
        $response->assertJsonValidationErrors([
            'phone_number' => [
                trans('validation.numeric', ['attribute' => 'phone number']),
                trans('validation.digits', ['attribute' => 'phone number', 'digits' => 11]),
            ],
            'code' => [
                trans('validation.numeric', ['attribute' => 'code']),
                trans('validation.digits', ['attribute' => 'code', 'digits' => 6]),
            ],
        ]);
    }
}
