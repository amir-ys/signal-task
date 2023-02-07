<?php

namespace Tests\Feature\Models;

use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarehouseTest extends TestCase
{
    use RefreshDatabase;

    public function test_insert_data_to_database()
    {
        $data = Warehouse::factory()->make()->toArray();

        Warehouse::create($data);

        $this->assertDatabaseCount('warehouses' , 1);
        $this->assertDatabaseHas('warehouses' , $data);

    }
}
