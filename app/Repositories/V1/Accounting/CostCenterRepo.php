<?php

namespace App\Repositories\V1\Accounting;

use App\Models\Tenant\Accounting\CostCenter\CostCenter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CostCenterRepo
{
    public function query()
    {
        return CostCenter::query();
    }

    public function findOrFail(string $id): Model
    {
        return CostCenter::findOrFail($id);
    }

    public function create(array $data): Model
    {
        return CostCenter::create($data);
    }

    public function update(Model $model, array $data): bool
    {
        return $model->update($data);
    }

    public function destroy(array $ids): void
    {
        CostCenter::destroy($ids);
    }
}
