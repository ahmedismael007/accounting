<?php

namespace App\Repositories\V1\Common;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepo
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function query(?array $with = [])
    {
        return $this->model->newQuery()->when($with, fn($q) => $q->with($with));
    }

    public function findOrFail(int $id, array $with = []): Model
    {
        return $this->query($with)->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Model
    {
        $model = $this->findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id) > 0;
    }
}
