<?php

namespace App\Http\Controllers\Tenant\Accounting\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\Projects\ProjectRequest;
use App\Services\V1\Accounting\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(protected ProjectService $projectService)
    {
    }

    public function index(Request $request)
    {
        $data = $this->projectService->index($request);

        return response()->json($data, 200);
    }

    public function store(ProjectRequest $request)
    {
        try {
            $project = $this->projectService->store($request->validated());

            return response()->json([
                'message' => trans('crud.created'),
                'data' => $project
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function show(string $id)
    {
        try {
            $project = $this->projectService->show($id);

            return response()->json(['data' => $project], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function update(ProjectRequest $request, string $id)
    {
        try {
            $this->projectService->update($request->validated(), $id);

            return response()->json(['message' => trans('crud.updated')], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function destroy(Request $request)
    {
        $ids = $request->input('ids');

        if (!is_array($ids)) {
            return response()->json(['error' => 'Invalid request format'], 422);
        }

        try {
            $this->projectService->destroy($ids);

            return response()->json(['message' => trans('crud.deleted')], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
