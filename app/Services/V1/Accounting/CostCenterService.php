<?php

namespace App\Services\V1\Accounting;

use App\Repositories\V1\Accounting\CostCenterRepo;
use App\Services\V1\Common\QueryBuilderService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class CostCenterService
{
    public function __construct(
        protected CostCenterRepo      $repo,
        protected QueryBuilderService $queryBuilder
    )
    {
    }

    public function index(Request $request)
    {
        $query = $this->repo->query();
        return $this->queryBuilder->applyQuery($request, $query);
    }

    public function store(array $data): Model
    {
        return $this->repo->create($data);
    }

    public function show(string $id): Model
    {
        return $this->repo->findOrFail($id);
    }

    public function update(array $data, string $id): bool
    {
        $costCenter = $this->repo->findOrFail($id);
        return $this->repo->update($costCenter, $data);
    }

    public function destroy(array $ids): void
    {
        $this->repo->destroy($ids);
    }
}
