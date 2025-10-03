<?php

namespace App\Http\Controllers\Tenant\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Inventory\CreateItemRequest;
use App\Http\Requests\Tenant\Inventory\UpdateItemRequest;
use App\Services\V1\Inventory\ItemService;
use Illuminate\Http\Request;

class ItemController extends Controller
{

    public function __construct(
        protected ItemService $itemService
    )
    {
    }

    public function index(Request $request)
    {
        $data = $this->itemService->index($request);
        return response()->json($data, 200);
    }

    public function store(CreateItemRequest $request)
    {
        $item = $this->itemService->store(
            $request->validated(),
            $request->file('media') ?? []
        );

        return response()->json([
            'message' => trans('crud.created'),
            'data' => $item->load('media')
        ], 201);
    }

    public function show(string $id)
    {
        $item = $this->itemService->show($id);
        return response()->json(['data' => $item->load('media')], 200);
    }

    public function update(UpdateItemRequest $request, string $id)
    {
        $this->itemService->update(
            $request->validated(),
            $id,
            $request->file('media') ?? []
        );

        return response()->json(['message' => trans('crud.updated')], 200);
    }

    public function destroy(Request $request)
    {
        $ids = $request->input('ids');
        if (!is_array($ids)) {
            return response()->json(['error' => 'Invalid request format'], 422);
        }

        $this->itemService->destroy($ids);

        return response()->json(['message' => trans('crud.deleted')]);
    }
}
