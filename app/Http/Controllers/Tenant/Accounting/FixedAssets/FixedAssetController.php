<?php

namespace App\Http\Controllers\Tenant\Accounting\FixedAssets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\FixedAssets\FixedAssetRequest;
use App\Models\Tenant\Accounting\FixedAssets\FixedAsset;
use App\Traits\QueryBuilder;
use Illuminate\Http\Request;

class FixedAssetController extends Controller
{
    use QueryBuilder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FixedAsset::query();
        $data = $this->applyQuery($request, $query);

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FixedAssetRequest $request)
    {
        FixedAsset::create($request->validated());
        return response()->json(['message' => 'تم إضافة الأصل بنجاح.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $fixed_asset = FixedAsset::findOrFail($id);

        return response()->json(['data' => $$fixed_asset], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FixedAssetRequest $request, string $id)
    {
        $fixed_asset = FixedAsset::find($id);

        if (!$fixed_asset) {
            return response()->json(['message' => 'العنصر غير موجود.'], 404);
        }

        $fixed_asset->update($request->validated());

        return response()->json(['message' => 'تم تعديل الأصل بنجاح.'], 200);
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

        FixedAsset::destroy($ids);

        return response()->json(['message' => 'تم حذف الأصل بنجاح.']);
    }
}
