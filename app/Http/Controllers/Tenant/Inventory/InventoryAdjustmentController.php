<?php

namespace App\Http\Controllers\Tenant\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Inventory\CreateInventoryAdjustmentRequest;
use App\Http\Requests\Tenant\Inventory\UpdateInventoryAdjustmentRequest;
use App\Services\V1\Inventory\InventoryAdjustmentService;
use Illuminate\Http\Request;

class InventoryAdjustmentController extends Controller
{
    public function __construct(protected InventoryAdjustmentService $service) {}

    public function index(Request $request)
    {
        $data = $this->service->index($request);
        return response()->json($data, 200);
    }

    public function store(CreateInventoryAdjustmentRequest $request)
    {
        $adjustment = $this->service->store($request->validated());
        return response()->json(['message' => trans('crud.created'), 'data' => $adjustment], 201);
    }

    public function show(string $id)
    {
        $adjustment = $this->service->show($id);
        return response()->json(['data' => $adjustment], 200);
    }

    public function update(UpdateInventoryAdjustmentRequest $request, string $id)
    {
        $this->service->update($request->validated(), $id);
        return response()->json(['message' => trans('crud.updated')], 200);
    }

    public function destroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids)) {
            return response()->json(['error' => 'Invalid request format'], 422);
        }

        $this->service->destroy($ids);
        return response()->json(['message' => trans('crud.deleted')], 200);
    }
}
