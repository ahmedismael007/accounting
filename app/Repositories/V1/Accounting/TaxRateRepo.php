<?php

namespace App\Repositories\V1\Accounting;

use App\Models\Tenant\Accounting\Accountants\TaxRate;

class TaxRateRepo
{
    public function index()
    {
        return TaxRate::query();
    }

    public function store(array $data)
    {
        return TaxRate::create($data);
    }

    public function update(array $data, string $id)
    {
        $taxRate = $this->show($id);

        if (isset($data['name'])) {
            $data['name'] = array_merge($taxRate->name ?? [], $data['name']);
        }

        $taxRate->update($data);

        return $taxRate;
    }

    public function show(string $id)
    {
        return TaxRate::findOrFail($id);
    }

    public function destroy(array $ids)
    {
        return TaxRate::destroy($ids);
    }
}
