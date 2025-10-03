<?php

namespace App\Services\V1\Inventory;

use App\Repositories\V1\Inventory\ItemRepo;
use App\Services\V1\Common\QueryBuilderService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemService
{
    public function __construct(
        protected ItemRepo            $itemRepo,
        protected QueryBuilderService $queryBuilder
    )
    {
    }

    public function index(Request $request)
    {
        $query = $this->itemRepo->applyQuery();

        return $this->queryBuilder->applyQuery($request, $query);
    }

    public function store(array $data, ?array $files = []): Model
    {
        return DB::transaction(function () use ($data, $files) {
            $item = $this->itemRepo->create($data);

            if (!empty($files)) {
                foreach ($files as $file) {
                    $item->addMedia($file)->toMediaCollection('items');
                }
            }

            return $item;
        });
    }

    public function show(string $id): Model
    {
        return $this->itemRepo->findOrFail($id);
    }

    public function update(array $data, string $id, ?array $files = []): bool
    {
        return DB::transaction(function () use ($data, $id, $files) {
            $item = $this->itemRepo->findOrFail($id);
            $this->itemRepo->update($item, $data);

            if (!empty($files)) {
                $item->clearMediaCollection('items');
                foreach ($files as $file) {
                    $item->addMedia($file)->toMediaCollection('items');
                }
            }

            return true;
        });
    }

    public function destroy(array $ids): void
    {
        DB::transaction(function () use ($ids) {
            $this->itemRepo->destroy($ids);
        });
    }
}
