<?php

namespace App\Http\Controllers\Tenant\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Inventory\CreateProductRequest;
use App\Services\V1\Inventory\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function __construct(
        protected ProductService $productService
    )
    {
    }

    public function index(Request $request)
    {
        $data = $this->productService->index($request);
        return response()->json($data, 200);
    }

    public function store(CreateProductRequest $request)
    {
        $product = $this->productService->store(
            $request->validated(),
            $request->file('media') ?? []
        );

        return response()->json([
            'message' => trans('crud.created'),
            'data' => $product->load('media')
        ], 201);
    }

    public function show(string $id)
    {
        $product = $this->productService->show($id);
        return response()->json(['data' => $product->load('media')], 200);
    }

    public function update(CreateProductRequest $request, string $id)
    {
        $this->productService->update(
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

        $this->productService->destroy($ids);

        return response()->json(['message' => trans('crud.deleted')]);
    }
}
