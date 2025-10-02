<?php

namespace App\Repositories\V1\Inventory;

use App\Models\Tenant\Inventory\Product;
use Illuminate\Database\Eloquent\Model;

class ProductRepo
{
    public function applyQuery()
    {
        return Product::query();
    }

    public function create(array $data): Model
    {
        return Product::create($data);
    }

    public function findOrFail(string $id): Model
    {
        return Product::findOrFail($id);
    }

    public function update(Model $product, array $data): bool
    {
        return $product->update($data);
    }

    public function destroy(array $ids): void
    {
        Product::destroy($ids);
    }
}
