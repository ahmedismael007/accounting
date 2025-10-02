<?php

namespace App\Repositories\V1\Inventory;

use App\Models\Tenant\Inventory\Warehouse;
use Illuminate\Database\Eloquent\Model;

class WarehouseRepo
{
    public function query()
    {
        return Warehouse::query();
    }

    public function findOrFail(string $id)
    {
        return Warehouse::findOrFail($id);
    }

    public function create(array $data): Model
    {
        return Warehouse::create($data);
    }

    public function update(Model $model, array $data): bool
    {
        return $model->update($data);
    }

    public function destroy(array $ids): void
    {
        Warehouse::destroy($ids);
    }
}
