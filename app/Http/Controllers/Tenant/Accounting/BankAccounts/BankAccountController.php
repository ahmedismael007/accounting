<?php

namespace App\Http\Controllers\Tenant\Accounting\BankAccounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\BankAccounts\BankAccountRequest;
use App\Models\Tenant\Accounting\Accountants\Account;
use App\Models\Tenant\Accounting\BankAccounts\BankAccount;
use App\Services\v1\AccountCodeGeneratorService;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bank_accounts = BankAccount::with('account')->latest()->get();

        return response()->json(['data' => $bank_accounts], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BankAccountRequest $request, AccountCodeGeneratorService $code_generator)
    {
        $bank_account = BankAccount::create($request->validated());

        $account_code = $code_generator->generate(3);

        Account::create([
            'account_code' => $account_code,
            'classification' => 'ASSET',
            'name_ar' => $bank_account['name_ar'],
            'name_en' => $bank_account['name_en'],
            'activity' => 'CASH',
            'parent_id' => 3,
            'show_in_expense_claims' => false,
            'bank_account_id' => $bank_account['id'],
            'is_locked' => false,
            'lock_reason' => null,
            'is_system' => false,
            'is_payment_enabled' => true,
        ]);

        return response()->json(['message' => 'تم إضافة الحساب البنكي بنجاح.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bank_account = BankAccount::findOrFail($id);

        return response()->json(['data' => $bank_account], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BankAccountRequest $request, string $id)
    {
        $bank_account = BankAccount::findOrFail($id);
        $validatedData = $request->validated();

        // Update bank account
        $bank_account->update($validatedData);

        // Update associated account if it exists
        Account::where('bank_account_id', $id)->update([
            'name_ar' => $validatedData['name_ar'],
            'name_en' => $validatedData['name_en'],
        ]);

        return response()->json(['message' => 'تم تعديل بيانات الحساب البنكي بنجاح.'], 200);
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

        Account::whereIn('bank_account_id', $ids)->delete();

        BankAccount::destroy($ids);

        return response()->json(['message' => 'تم حذف الحساب البنكي بنجاح.']);
    }
}
