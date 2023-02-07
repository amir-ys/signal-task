<?php

namespace Tests\Feature\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_insert_data_to_database()
    {
       $data =  User::factory()->unverified()->user()->make()->toArray();

        User::create($data);

        $this->assertDatabaseCount( 'users' , 1);
        $this->assertDatabaseHas('users' ,$data);

    }

    public function test_is_admin_method()
    {
        $user = User::factory()->admin()->create();
        $this->assertEquals($user->isAdmin() , true );
    }
}
