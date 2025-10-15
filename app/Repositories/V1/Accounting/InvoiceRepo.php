<?php

namespace App\Repositories\V1\Accounting;

use App\Models\Tenant\Accounting\Sales\Invoice;
use Illuminate\Database\Eloquent\Model;

class InvoiceRepo
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
    }

    public function query()
    {
        return Invoice::query();
    }

    public function findOrFail(string $id): Model
    {
        return Invoice::findOrFail($id);
    }

    public function create(array $data): Model
    {
        return Invoice::create($data);
    }

    public function update(string $id, array $data): bool
    {
        return $this->findOrFail($id)->update($data);

    }

    public function destroy(array $ids): void
    {
        Invoice::destroy($ids);
    }
}
