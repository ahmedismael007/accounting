<?php

namespace App\Services\V1\Payroll;

use App\Repositories\V1\Payroll\PayrollRepo;
use App\Services\V1\Common\QueryBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

class PayrollService
{
    public function __construct(
        protected PayrollRepo $repo,
        protected QueryBuilderService $queryBuilder
    ) {}

    public function index(Request $request)
    {
        $query = $this->repo->query();
        return $this->queryBuilder->applyQuery($request, $query);
    }

    public function show(int $id): Model
    {
        return $this->repo->findOrFail($id);
    }

    public function store(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            return $this->repo->create($data);
        });
    }

    public function update(int $id, array $data): Model
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->repo->update($id, $data);
        });
    }

    public function destroy(array $ids): bool
    {
        return DB::transaction(function () use ($ids) {
            foreach ($ids as $id) {
                $this->repo->delete($id);
            }
            return true;
        });
    }
}
