<?php

namespace App\Http\Controllers\Tenant\Accounting\Accountants;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\Accountants\AccountRequest;
use App\Models\Tenant\Accounting\Accountants\Account;
use App\Models\Tenant\Accounting\BankAccounts\BankAccount;
use App\Services\v1\AccountCodeGeneratorService;
use App\Traits\QueryBuilder;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    use QueryBuilder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Account::with('children')->whereNull('parent_id');
        $data = $this->applyQuery($request, $query);

           $translatedData = $this->translateActivities($data);

    return response()->json($translatedData, 200);
    }

    private function translateActivities($accounts)
    {
        foreach ($accounts as &$account) {
            // Translate activity using the translation file
            $account['activity'] = trans("enum.{$account['activity']}") ?? $account['activity'];

            if (!empty($account['children'])) {
                $account['children'] = $this->translateActivities($account['children']);
            }
        }

        return $accounts;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AccountRequest $request, AccountCodeGeneratorService $code_generator)
    {
        $validated = $request->validated();

        $account_code = $code_generator->generate($validated['parent_id']);
        $validated['account_code'] = $account_code;

        $bank_account = null;

        if (!empty($validated['is_bank']) && $validated['is_bank'] == true) {
            $bank_account = BankAccount::create([
                'name' => $validated['name'],
                 'type'    => 'BANK_ACCOUNT',
                'currency' => 'SAR',
            ]);

            $validated['bank_account_id'] = $bank_account->id;
        }

        Account::create($validated);

        return response()->json(['message' => 'تم إضافة الحساب بنجاح.'], 201);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $account = Account::findOrFail($id);

        return response()->json(['data' => $account], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AccountRequest $request, string $id, AccountCodeGeneratorService $code_generator)
    {
        $account = Account::find($id);

        if (!$account) {
            return response()->json(['message' => 'العنصر غير موجود.'], 404);
        }

        if ($account['is_system']) {
            return response()->json(['message' => 'هذا الحساب خاص بالنظام ولا يمكن تعديله.'], 422);
        }

        $data = $request->validated();

        if (isset($data['parent_id']) && $data['parent_id'] != $account->parent_id) {
            $data['account_code'] = $code_generator->generate($data['parent_id']);
        }

        $account->update($data);

        if ($account->bank_account_id) {
            BankAccount::where('id', $account->bank_account_id)->update([
                'name_ar' => $account['name_ar'],
                'name_en' => $account['name_en'],
            ]);
        }

        return response()->json(['message' => 'تم تعديل بيانات الحساب بنجاح.'], 200);
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

        foreach ($ids as $id) {
            $account = Account::findOrFail($id);

            if ($account->is_system) {
                return response()->json(['message' => 'هذا الحساب خاص بالنظام ولا يمكن حذفه.'], 422);
            }

            if ($account['bank_account_id']) {
                BankAccount::where('id', $account['bank_account_id'])->destroy();
            }
        }

        Account::destroy($ids);

        return response()->json(['message' => 'تم حذف الحساب بنجاح.']);
    }
}
