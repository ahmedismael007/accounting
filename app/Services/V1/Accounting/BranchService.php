<?php

namespace App\Services\V1\Accounting;

use App\Models\Tenant\Accounting\Branches\Branch;
use App\Repositories\V1\Accounting\BranchRepo;
use App\Services\V1\Common\QueryBuilderService;
use Exception;
use Illuminate\Http\Request;
use function trans;

class BranchService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected BranchRepo $branchRepo, protected QueryBuilderService $queryBuilderService)
    {

    }

    public function index(Request $request)
    {
        $query = $this->branchRepo->index();

        return $this->queryBuilderService->applyQuery($request, $query);
    }

    public function store(array $data): Branch
    {
        return $this->branchRepo->store($data);
    }

    public function show(string $id): Branch
    {
        return $this->branchRepo->show($id);
    }

    public function update(array $data, $id): Branch
    {

        return $this->branchRepo->update($data, $id);
    }

    public function destroy(array $ids)
    {
        return $this->branchRepo->destroy($ids);
    }
}
