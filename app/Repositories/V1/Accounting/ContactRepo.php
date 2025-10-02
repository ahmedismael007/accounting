<?php

namespace App\Repositories\V1\Accounting;

use App\Models\Tenant\Accounting\Contacts\Contact;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ContactRepo
{
    public function query()
    {
        return Contact::query();
    }

    public function findOrFail(string $id): Model
    {
        return Contact::findOrFail($id);
    }

    public function create(array $data): Model
    {
        return Contact::create($data);
    }

    public function update(Model $model, array $data): bool
    {
        return $model->update($data);
    }

    public function destroy(array $ids): void
    {
        Contact::destroy($ids);
    }
}
