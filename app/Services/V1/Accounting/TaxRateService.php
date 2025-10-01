<?php

namespace App\Services\V1\Accounting;

use App\Models\Tenant\Accounting\Accountants\TaxRate;
use App\Repositories\V1\Accounting\TaxRateRepo;
use App\Services\V1\Common\QueryBuilderService;
use Exception;
use Illuminate\Http\Request;

class TaxRateService
{
    public function __construct(
        protected TaxRateRepo         $taxRateRepo,
        protected QueryBuilderService $queryBuilderService
    )
    {
    }

    public function index(Request $request)
    {
        $query = $this->taxRateRepo->index();
        return $this->queryBuilderService->applyQuery($request, $query);
    }

    public function store(array $data): TaxRate
    {
        return $this->taxRateRepo->store($data);
    }

    public function show(string $id): TaxRate
    {
        return $this->taxRateRepo->show($id);
    }

    public function update(array $data, string $id)
    {
        $taxRate = $this->taxRateRepo->show($id);

        if ($taxRate->is_system) {
            throw new Exception(trans('accounting.tax_not_editable'));
        }

        return $this->taxRateRepo->update($data, $id);
    }

    public function destroy(array $ids): int
    {
        foreach ($ids as $id) {
            $taxRate = $this->taxRateRepo->show($id);

            if ($taxRate->is_system) {
                throw new Exception(trans('accounting.tax_not_deletable'));
            }
        }

        return $this->taxRateRepo->destroy($ids);
    }
}
