<?php

namespace App\Repositories\V1\Accounting;

use App\Models\Tenant\Accounting\Accountants\Journal;

class JournalRepo
{
    public function create(array $data): Journal
    {
        return Journal::create($data);
    }

    public function findOrFail(int $id): Journal
    {
        return Journal::findOrFail($id);
    }

    public function findWithRelations(int $id, array $relations = []): Journal
    {
        return Journal::with($relations)->findOrFail($id);
    }

    public function findWhereIn(string $column, array $values)
    {
        return Journal::whereIn($column, $values)->get();
    }

    public function deleteMany(array $ids): void
    {
        Journal::destroy($ids);
    }
}
