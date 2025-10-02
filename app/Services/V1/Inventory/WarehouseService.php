<?php

namespace App\Services\V1\Inventory;

use App\Repositories\V1\Inventory\WarehouseRepo;
use App\Repositories\V1\Accounting\AccountRepo;
use App\Services\V1\Common\AccountCodeGeneratorService;
use App\Services\V1\Common\QueryBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Exception;
use function app;

class WarehouseService
{
    public function __construct(
        protected WarehouseRepo               $warehouseRepo,
        protected AccountRepo                 $accountRepo,
        protected AccountCodeGeneratorService $accountCodeGenerator,
        protected QueryBuilderService         $queryBuilder
    )
    {
    }

    public function index(Request $request)
    {
        $query = $this->warehouseRepo->query();
        return $this->queryBuilder->applyQuery($request, $query);
    }

    public function store(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $parent_id = 10;

            $account_code = $this->accountCodeGenerator->generate($parent_id);

            $locale = app()->getLocale();

            $account = $this->accountRepo->store([
                'account_code' => $account_code,
                'classification' => 'ASSET',
                'name' => [
                    $locale => $data['name']
                ],
                'activity' => 'OPERATING',
                'parent_id' => $parent_id,
                'children' => [],
                'show_in_expense_claims' => false,
                'bank_account_id' => null,
                'is_locked' => true,
                'lock_reason' => '',
                'is_system' => false,
                'is_payment_enabled' => true,
            ]);

            $data['account_id'] = $account->id;

            $warehouse = $this->warehouseRepo->create($data);

            return $warehouse;
        });
    }

    public function update(array $data, string $id): bool
    {
        DB::transaction(function () use ($data, $id) {
            $warehouse = $this->warehouseRepo->findOrFail($id);

            $this->warehouseRepo->update($warehouse, $data);

            $account = $this->accountRepo->show($warehouse->account_id);

            $locale = app()->getLocale();

            return $this->accountRepo->update($account->id, [
                'name' => [
                    $locale => $data['name']
                ],
            ]);
        });

        return true;
    }

    public function show(string $id): Model
    {
        return $this->warehouseRepo->findOrFail($id);
    }

    public function destroy(array $ids): void
    {
        DB::transaction(function () use ($ids) {
            $accountIds = [];

            foreach ($ids as $id) {
                $warehouse = $this->warehouseRepo->findOrFail($id);

                if ($warehouse->products()->exists()) {
                    throw new Exception(trans('inventory.warehouse_has_products'));
                }

                if ($warehouse->account_id) {
                    $accountIds[] = $warehouse->account_id;
                }
            }

            $this->warehouseRepo->destroy($ids);

            if (!empty($accountIds)) {
                $this->accountRepo->destroy($accountIds);
            }
        });
    }

}
