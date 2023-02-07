<?php

namespace Tests\Feature\Middleware;

use App\Http\Middleware\CheckUserHasAdminRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CheckUserHasAdminRoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_admin()
    {
        $user = User::factory()->admin()->create();
        Sanctum::actingAs($user, ['*']);

        $request = Request::create('/test', 'GET');

        $middleware = new CheckUserHasAdminRole();
        $response = $middleware->handle($request, function () {});

        $this->assertEquals($response, null);
    }

    public function test_user_is_not_admin()
    {
        $user = User::factory()->user()->create();
        Sanctum::actingAs($user, ['*']);

        $request = Request::create('/test', 'GET');

        $middleware = new CheckUserHasAdminRole();
        $response = $middleware->handle($request, function () {});

        $this->assertEquals($response->getStatusCode(), 302);
    }

}
