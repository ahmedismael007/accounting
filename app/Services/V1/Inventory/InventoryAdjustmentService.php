<?php

namespace App\Services\V1\Inventory;

use App\Repositories\V1\Inventory\InventoryAdjustmentRepo;
use App\Services\V1\Accounting\JournalService;
use App\Services\V1\Common\QueryBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class InventoryAdjustmentService
{
    public function __construct(
        protected InventoryAdjustmentRepo $repo,
        protected JournalService          $journalService,
        protected QueryBuilderService     $queryBuilder
    )
    {
    }

    public function index(Request $request)
    {
        $query = $this->repo->index();
        return $this->queryBuilder->applyQuery($request, $query);
    }

    public function store(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $data['total_adjustment_amount'] = $data['qty'] * $data['inventory_value'];

            $adjustment = $this->repo->create($data);

            $adjustment->update([
                'adjustment_id' => 'ADJ-' . str_pad($adjustment->id, 4, '0', STR_PAD_LEFT)
            ]);

            $this->journalService->createFromAdjustment($adjustment);

            return $adjustment;
        });
    }

    public function show(string $id): Model
    {
        return $this->repo->findOrFail($id);
    }

    public function update(array $data, string $id): bool
    {
        return DB::transaction(function () use ($data, $id) {
            $adjustment = $this->repo->findOrFail($id);
            $data['total_adjustment_amount'] = $data['qty'] * $data['inventory_value'];

            return $this->repo->update($adjustment, $data);
        });
    }

    public function destroy(array $ids): void
    {
        DB::transaction(function () use ($ids) {
            $this->repo->destroy($ids);
        });
    }
}
