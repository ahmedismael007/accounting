<?php

namespace App\Repositories\V1\Accounting;

use App\Models\Tenant\Accounting\Branches\Branch;
use http\Env\Request;

class BranchRepo
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {

    }

    public function index()
    {
        return Branch::query();
    }

    public function store(array $data)
    {
        return Branch::create($data);
    }

    public function show(string $id)
    {
        return Branch::findOrFail($id);
    }

    public function update(array $data, string $id)
    {
        $branch = $this->show($id);

        return $branch->update($data);
    }

    public function destroy(array $ids)
    {
        return Branch::destroy($ids);
    }
}
