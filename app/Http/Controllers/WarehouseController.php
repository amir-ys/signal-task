<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseRequest;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use App\Responses\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class WarehouseController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $wareHouses = Warehouse::query()->paginate();
        return WarehouseResource::collection($wareHouses);
    }

    public function show(Warehouse $warehouse): JsonResponse
    {
        return Response::data('success', new WarehouseResource($warehouse));
    }

    public function store(WarehouseRequest $request): JsonResponse
    {
        $warehouse = Warehouse::create([
            'name' => $request->name,
            'inventory' => $request->inventory,
            'entry_date' => $request->entry_date,
        ]);

        return Response::succes('warehouse created successfully', $warehouse);
    }

    public function update(WarehouseRequest $request, Warehouse $warehouse): JsonResponse
    {
        $warehouse = $warehouse->update([
            'name' => $request->name,
            'inventory' => $request->inventory,
            'entry_date' => $request->entry_date,
        ]);

        return Response::succes('warehouse updated successfully', $warehouse);
    }

    public function destroy(Warehouse $warehouse): JsonResponse
    {
        $warehouse->delete();
        return Response::succes("warehouse with name {$warehouse->name} deleted successfully");
    }
}
