<?php

namespace App\Services\V1\Accounting;

use App\Repositories\V1\Accounting\ProjectRepo;
use App\Services\V1\Common\QueryBuilderService;
use App\Models\Tenant\Accounting\Projects\Project;
use Illuminate\Http\Request;

class ProjectService
{
    public function __construct(
        protected ProjectRepo         $projectRepo,
        protected QueryBuilderService $queryBuilderService
    )
    {
    }

    public function index(Request $request)
    {
        $query = $this->projectRepo->index();

        return $this->queryBuilderService->applyQuery($request, $query);
    }

    public function store(array $data): Project
    {
        return $this->projectRepo->store($data);
    }

    public function show(string $id): Project
    {
        return $this->projectRepo->show($id);
    }

    public function update(array $data, string $id): Project
    {
        return $this->projectRepo->update($data, $id);
    }

    public function destroy(array $ids): int
    {
        return $this->projectRepo->destroy($ids);
    }
}
