<?php

namespace App\Repositories\V1\Inventory;

use App\Models\Tenant\Inventory\Item;
use Illuminate\Database\Eloquent\Model;

class ItemRepo
{
    public function applyQuery()
    {
        return Item::query();
    }

    public function create(array $data): Model
    {
        return Item::create($data);
    }

    public function findOrFail(string $id): Model
    {
        return Item::findOrFail($id);
    }

    public function update(Model $Item, array $data): bool
    {
        return $Item->update($data);
    }

    public function destroy(array $ids): void
    {
        Item::destroy($ids);
    }
}
