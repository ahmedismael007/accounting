<?php

namespace App\Repositories\V1\Inventory;

use App\Models\Tenant\Inventory\InventoryAdjustment;
use Illuminate\Database\Eloquent\Model;

class InventoryAdjustmentRepo
{
    public function index()
    {
        return InventoryAdjustment::query();
    }

    public function findOrFail(string $id): Model
    {
        return InventoryAdjustment::findOrFail($id);
    }

    public function create(array $data): Model
    {
        return InventoryAdjustment::create($data);
    }

    public function update(Model $adjustment, array $data): bool
    {
        return $adjustment->update($data);
    }

    public function destroy(array $ids): int
    {
        return InventoryAdjustment::destroy($ids);
    }
}
