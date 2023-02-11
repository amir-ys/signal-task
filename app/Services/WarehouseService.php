<?php

namespace App\Services;

use App\Models\Warehouse;

class WarehouseService
{
    public function createWarehouse(array $data): Warehouse
    {
        return Warehouse::create([
            'name' => $data['name'],
            'inventory' => $data['inventory'],
            'entry_date' => $data['entry_date'],
        ]);
    }

    public function updateWarehouse(Warehouse $warehouse, array $data): bool
    {
        return $warehouse->update([
            'name' => $data['name'],
            'inventory' => $data['inventory'],
            'entry_date' => $data['entry_date'],
        ]);
    }

    public function deleteWarehouse(Warehouse $warehouse): bool
    {
        return $warehouse->delete();
    }

}
