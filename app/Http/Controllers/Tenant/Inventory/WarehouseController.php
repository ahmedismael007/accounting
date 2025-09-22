<?php

namespace App\Http\Controllers\Tenant\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Inventory\WarehouseRequest;
use App\Models\Tenant\Accounting\Accountants\Account;
use App\Models\Tenant\Inventory\Warehouse;
use App\Services\v1\AccountCodeGeneratorService;
use App\Traits\QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    use QueryBuilder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Warehouse::query();
        $data = $this->applyQuery($request, $query);

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WarehouseRequest $request, AccountCodeGeneratorService $accountCodeGenerator)
    {
        try {
            DB::beginTransaction();

            $warehouse = Warehouse::create($request->validated());

            $parent_id = 115;

            $account_code = $accountCodeGenerator->generate($parent_id);

            $account = Account::create([
                'account_code' => $account_code,
                'classification' => 'ASSET',
                'name_ar' => $warehouse['name'],
                'name_en' => $warehouse['name'],
                'activity' => 'OPERATING',
                'parent_id' => 115, //
                'children' => [], //
                'show_in_expense_claims' => false,
                'bank_account_id' => null,
                'is_locked' => true,
                'lock_reason' => '',
                'is_system' => false,
                'is_payment_enabled' => true,
            ]);

            $warehouse->update(['account_id' => $account->id]);

            DB::commit();

            return response()->json([
                'message' => 'تم إضافة المستودع بنجاح.',
                'data' => $warehouse
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'حدث خطأ أثناء إضافة المستودع.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $warehouse = Warehouse::findOrFail($id);

        return response()->json(['data' => $warehouse], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WarehouseRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $warehouse = Warehouse::findOrFail($id);

            $warehouse->update($request->validated());

            $warehouse_account = Account::findOrFail($warehouse['account_id']);

            $warehouse_account->update([
                'name_ar' => $warehouse['name'],
                'name_en' => $warehouse['name']
            ]);

            return response()->json(['message' => 'تم تعديل بيانات المستودع بنجاح.'], 200);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'حدث خطأ أثناء التعديل'], 422);
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

        DB::beginTransaction();

        try {
            foreach ($ids as $id) {
                $warehouse = Warehouse::findOrFail($id);

                if ($warehouse->products()->exists()) {
                    return response()->json([
                        'message' => "لا يمكن حذف المستودع: {$warehouse->name} لأنه يحتوي على منتجات."
                    ], 400);
                }
            }

            Warehouse::destroy($ids);

            DB::commit();

            return response()->json(['message' => 'تم حذف المستودع/المستودعات بنجاح.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'حدث خطأ أثناء حذف المستودعات.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
