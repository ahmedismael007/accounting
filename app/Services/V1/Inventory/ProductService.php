<?php

namespace App\Services\V1\Inventory;

use App\Repositories\V1\Inventory\ProductRepo;
use App\Services\V1\Common\QueryBuilderService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function __construct(
        protected ProductRepo         $productRepo,
        protected QueryBuilderService $queryBuilder
    )
    {
    }

    public function index(Request $request)
    {
        $query = $this->productRepo->applyQuery();

        return $this->queryBuilder->applyQuery($request, $query);
    }

    public function store(array $data, ?array $files = []): Model
    {
        return DB::transaction(function () use ($data, $files) {
            $product = $this->productRepo->create($data);

            if (!empty($files)) {
                foreach ($files as $file) {
                    $product->addMedia($file)->toMediaCollection('products');
                }
            }

            return $product;
        });
    }

    public function show(string $id): Model
    {
        return $this->productRepo->findOrFail($id);
    }

    public function update(array $data, string $id, ?array $files = []): bool
    {
        return DB::transaction(function () use ($data, $id, $files) {
            $product = $this->productRepo->findOrFail($id);
            $this->productRepo->update($product, $data);

            if (!empty($files)) {
                $product->clearMediaCollection('products');
                foreach ($files as $file) {
                    $product->addMedia($file)->toMediaCollection('products');
                }
            }

            return true;
        });
    }

    public function destroy(array $ids): void
    {
        DB::transaction(function () use ($ids) {
            $this->productRepo->destroy($ids);
        });
    }
}
