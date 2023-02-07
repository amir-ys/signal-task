<?php

namespace Tests\Feature\Controllers;

use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class WarehouseControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_method()
    {
        Warehouse::factory()->count(10)->create();
        $this->getJson(route('warehouses.index'))
            ->assertOk();
    }

    public function test_show_method()
    {
        $warehouse = Warehouse::factory()->create();
        $response = $this->getJson(route('warehouses.show', $warehouse->id))
            ->assertOk();

        $response->assertJson(['message' => 'success']);
    }

    public function test_store_method()
    {
        $this->withoutExceptionHandling();
        $data = Warehouse::factory()->make()->toArray();
        $response = $this->postJson(route('warehouses.store'), $data)
            ->assertOk();

        $response->assertJson(['message' => 'warehouse created successfully']);
        $response->assertJson(['data' => $data]);

        $this->assertDatabaseCount('warehouses', 1);
        $this->assertDatabaseHas('warehouses', $data);
    }

    public function test_update_method()
    {
        $warehouse = Warehouse::factory()->create();
        $data = Warehouse::factory()->make()->toArray();
        $response = $this->patchJson(route('warehouses.update', $warehouse->id), $data)
            ->assertOk();

        $response->assertJson(['message' => 'warehouse updated successfully']);
        $response->assertJson(['data' => $data]);

        $this->assertDatabaseCount('warehouses', 1);
        $this->assertDatabaseHas('warehouses', $data);
    }

    public function test_destroy_method()
    {
        $warehouse = Warehouse::factory()->create();
        $response = $this->deleteJson(route('warehouses.destroy', $warehouse->id))
            ->assertOk();

        $response->assertJson(['message' => "warehouse with name {$warehouse->name} deleted successfully"]);

        $this->assertDatabaseCount('warehouses', 0);
        $this->assertDatabaseMissing('warehouses', $warehouse->toArray());
    }

    public function test_validation_request_for_store_warehouse_method()
    {

        $response = $this->postJson(route('warehouses.store'), []);
        $response->assertJsonValidationErrors([
            'name' => trans('validation.required', ['attribute' => 'name']),
            'inventory' => trans('validation.required', ['attribute' => 'inventory']),
            'entry_date' => trans('validation.required', ['attribute' => 'entry date']),
        ]);

        $data = ['name' => 1, 'inventory' => Str::random(5), 'date' => Str::random(5)];
        $response = $this->postJson(route('warehouses.store'), $data);
        $response->assertJsonValidationErrors([
            'name' => [
                trans('validation.string', ['attribute' => 'name']),
                trans('validation.min', ['attribute' => 'name', 'min' => 2]),
            ],
            'inventory' => [
                trans('validation.numeric', ['attribute' => 'inventory']),
            ],
        ]);
    }


    public function test_validation_request_for_update_warehouse_method()
    {
        $warehouse = Warehouse::factory()->create();

        $response = $this->patchJson(route('warehouses.update', $warehouse->id), []);
        $response->assertJsonValidationErrors([
            'name' => trans('validation.required', ['attribute' => 'name']),
            'inventory' => trans('validation.required', ['attribute' => 'inventory']),
            'entry_date' => trans('validation.required', ['attribute' => 'entry date']),
        ]);

        $data = ['name' => 1, 'inventory' => Str::random(5), 'date' => Str::random(5)];
        $response = $this->patchJson(route('warehouses.update', $warehouse->id), $data);
        $response->assertJsonValidationErrors([
            'name' => [
                trans('validation.string', ['attribute' => 'name']),
                trans('validation.min', ['attribute' => 'name', 'min' => 2]),
            ],
            'inventory' => [
                trans('validation.numeric', ['attribute' => 'inventory']),
            ],
        ]);
    }


}
