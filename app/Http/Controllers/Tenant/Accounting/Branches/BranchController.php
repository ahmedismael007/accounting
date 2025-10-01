<?php

namespace App\Http\Controllers\Tenant\Accounting\Branches;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\Branches\CreateBranchRequest;
use App\Services\V1\Accounting\BranchService;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct(protected BranchService $branchService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $this->branchService->index($request);

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBranchRequest $request)
    {
        try {
            $branch = $this->branchService->store($request->validated());

            return response()->json([
                'message' => trans('crud.created'),
                'data' => $branch
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $branch = $this->branchService->show($id);

            return response()->json(['data' => $branch], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateBranchRequest $request, string $id)
    {
        try {
            $this->branchService->update($request->validated(), $id);

            return response()->json(['message' => trans('crud.updated')], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $ids = $request->input('ids');

        if (!is_array($ids)) {
            return response()->json(['error' => 'Invalid request format'], 422);
        }

        try {
            $this->branchService->destroy($ids);

            return response()->json(['message' => trans('crud.deleted')], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
