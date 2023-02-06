<?php

namespace Tests\Feature\Controllers;

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
}
