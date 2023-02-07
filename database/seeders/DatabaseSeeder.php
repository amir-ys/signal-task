<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Otp;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         User::factory()->admin()->state(['phone_number' => '09121001010'])->create();
         User::factory(2)->user()->create();

         Otp::factory()->state(['phone_number' => '09121001010'])->create();

          Warehouse::factory()->count(10)->create();
    }
}
