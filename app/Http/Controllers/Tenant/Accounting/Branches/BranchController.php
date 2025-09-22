<?php

namespace App\Http\Controllers\Tenant\Accounting\Branches;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\Branches\BranchRequest;
use App\Models\Tenant\Accounting\Branches\Branch;
use App\Traits\QueryBuilder;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    use QueryBuilder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Branch::query();
        $data = $this->applyQuery($request, $query);

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BranchRequest $request)
    {
        try {
            $branch = Branch::create($request->validated());

            return response()->json([
                'message' => 'تم إضافة الفرع بنجاح.',
                'data' => $branch
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء إضافة الفرع.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $branch = Branch::findOrFail($id);

        return response()->json(['data' => $branch], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BranchRequest $request, string $id)
    {
        $branch = Branch::findOrFail($id);

        $branch->update($request->validated());

        return response()->json(['message' => 'تم تعديل بيانات الفرع بنجاح.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $ids = $request->input('ids');

        if (!is_array($ids)) {
            return response()->json(['error' => 'Invalid request format'], 422);
        }

        Branch::destroy($ids);

        return response()->json(['message' => 'تم حذف الفرع بنجاح.']);
    }
}
