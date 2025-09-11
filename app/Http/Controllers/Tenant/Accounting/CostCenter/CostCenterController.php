<?php

namespace App\Http\Controllers\Tenant\Accounting\CostCenter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\CostCenter\CostCenterRequest;
use App\Models\Tenant\Accounting\CostCenter\CostCenter;
use App\Traits\QueryBuilder;
use Illuminate\Http\Request;

class CostCenterController extends Controller
{
    use QueryBuilder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CostCenter::query();
        $data = $this->applyQuery($request, $query);

        return response()->json($data, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CostCenterRequest $request)
    {
        CostCenter::create($request->validated());
        return response()->json(['message' => 'تم إضافة مركز التكلفة بنجاح.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cost_center = CostCenter::findOrFail($id);

        return response()->json(['data' => $cost_center], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CostCenterRequest $request, string $id)
    {
        $tax_rate = CostCenter::find($id);

        $tax_rate->update($request->validated());

        return response()->json(['message' => 'تم تعديل مركز التكلفة بنجاح.'], 200);
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

        CostCenter::destroy($ids);

        return response()->json(['message' => 'تم حذف مركز التكلفة بنجاح.']);
    }
}
