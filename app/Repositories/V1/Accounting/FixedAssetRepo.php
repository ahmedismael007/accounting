<?php

namespace App\Repositories\V1\Accounting;

use App\Models\Tenant\Accounting\FixedAssets\FixedAsset;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class FixedAssetRepo
{
    public function query()
    {
        return FixedAsset::query();
    }

    public function findOrFail(string $id): Model
    {
        return FixedAsset::findOrFail($id);
    }

    public function create(array $data): Model
    {
        return FixedAsset::create($data);
    }

    public function update(Model $model, array $data): bool
    {
        return $model->update($data);
    }

    public function destroy(array $ids): void
    {
        FixedAsset::destroy($ids);
    }
}
