<?php

namespace App\Http\Controllers\Tenant\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Inventory\CreateWarehouseRequest;
use App\Services\V1\Inventory\WarehouseService;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function __construct(
        protected WarehouseService $service
    )
    {
    }

    public function index(Request $request)
    {
        $data = $this->service->index($request);
        return response()->json($data, 200);
    }

    public function store(CreateWarehouseRequest $request)
    {
        $warehouse = $this->service->store($request->validated());
        return response()->json([
            'message' => trans('crud.created'),
            'data' => $warehouse
        ], 201);
    }

    public function update(CreateWarehouseRequest $request, string $id)
    {
        $this->service->update($request->validated(), $id);
        return response()->json(['message' => trans('crud.updated')], 200);
    }

    public function show(string $id)
    {
        $data = $this->service->show($id);
        return response()->json(['data' => $data], 200);
    }

    public function destroy(Request $request)
    {
        $this->service->destroy($request->input('ids'));
        return response()->json(['message' => trans('crud.deleted')]);
    }
}
