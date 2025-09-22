<?php

namespace App\Http\Controllers\Tenant\Accounting\Accountants;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\Accountants\TaxRateRequest;
use App\Models\Tenant\Accounting\Accountants\TaxRate;
use App\Traits\QueryBuilder;
use Illuminate\Http\Request;

class TaxRateController extends Controller
{
    use QueryBuilder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TaxRate::query();
        $data = $this->applyQuery($request, $query);

        $translated_data= $this->translateActivities($data);

        return response()->json($data, 200);
    }

    private function translateActivities($tax_rates)
    {
        foreach ($tax_rates as &$tax_rate) {
             $tax_rate['tax_type'] = trans("enum.{$tax_rate['tax_type']}") ?? $tax_rate['tax_type'];         
        }

        return $tax_rates;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaxRateRequest $request)
    {
        TaxRate::create($request->validated());
        return response()->json(['message' => 'تم إضافة الضريبة بنجاح.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tax_rate = TaxRate::findOrFail($id);

        return response()->json(['data' => $tax_rate], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaxRateRequest $request, string $id)
    {
        $tax_rate = TaxRate::find($id);

        if ($tax_rate['is_system'] == true) {
            return response()->json(['message' => 'هذا المعدل الضريبي خاص بالنظام ولا يمكن تعديله.'], 422);
        }

        if (!$tax_rate) {
            return response()->json(['message' => 'العنصر غير موجود.'], 404);
        }

        $tax_rate->update($request->validated());

        return response()->json(['message' => 'تم تعديل الضريبة بنجاح.'], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            $tax_rate = TaxRate::findOrFail($id);
            if ($tax_rate['is_system'] == true) {
                return response()->json(['message' => 'هذا المعدل الضريبي خاص بالنظام ولا يمكن حذفه.'], 422);
            }
        }

        if (!is_array($ids)) {
            return response()->json(['error' => 'Invalid request format'], 422);
        }

        TaxRate::destroy($ids);

        return response()->json(['message' => 'تم حذف الضريبة بنجاح.']);
    }
}
