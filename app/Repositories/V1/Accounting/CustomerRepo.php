<?php

namespace App\Repositories\V1\Accounting;

use App\Models\Tenant\Accounting\Customer\Customer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CustomerRepo
{
    public function query()
    {
        return Customer::query();
    }

    public function findOrFail(string $id): Model
    {
        return Customer::findOrFail($id);
    }

    public function create(array $data): Model
    {
        return Customer::create($data);
    }

    public function update(Model $model, array $data): bool
    {
        return $model->update($data);
    }

    public function destroy(array $ids): void
    {
        Customer::destroy($ids);
    }
}
