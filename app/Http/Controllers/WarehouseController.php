<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseRequest;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use App\Responses\Response;
use App\Services\WarehouseService;
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

    public function store(WarehouseRequest $request , WarehouseService $warehouseService): JsonResponse
    {
        $warehouse = $warehouseService->createWarehouse($request->validated());
        return Response::succes('warehouse created successfully', $warehouse);
    }

    public function update(WarehouseRequest $request, Warehouse $warehouse , WarehouseService $warehouseService): JsonResponse
    {
        $warehouse = $warehouseService->updateWarehouse($warehouse , $request->validated());
        return Response::succes('warehouse updated successfully', $warehouse);
    }

    public function destroy(Warehouse $warehouse , WarehouseService $warehouseService): JsonResponse
    {
        $warehouse = $warehouseService->deleteWarehouse($warehouse);
        return Response::succes("warehouse with name {$warehouse->name} deleted successfully");
    }
}
