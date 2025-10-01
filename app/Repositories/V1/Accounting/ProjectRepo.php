<?php

namespace App\Repositories\V1\Accounting;

use App\Models\Tenant\Accounting\Projects\Project;

class ProjectRepo
{
    public function index()
    {
        return Project::query();
    }

    public function store(array $data): Project
    {
        return Project::create($data);
    }

    public function show(string $id): Project
    {
        return Project::findOrFail($id);
    }

    public function update(array $data, string $id): Project
    {
        $project = $this->show($id);
        $project->update($data);

        return $project;
    }

    public function destroy(array $ids): int
    {
        return Project::destroy($ids);
    }
}
