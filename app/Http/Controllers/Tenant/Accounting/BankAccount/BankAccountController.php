<?php

namespace App\Http\Controllers\Tenant\Accounting\BankAccount;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Accounting\BankAccounts\CreateBankAccountRequest;
use App\Http\Requests\Tenant\Accounting\BankAccounts\UpdateBankAccountRequest;
use App\Services\V1\Accounting\BankAccountService;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function __construct(protected BankAccountService $bankAccountService)
    {
    }

    public function index(Request $request)
    {
        $data = $this->bankAccountService->index($request);
        return response()->json($data, 200);
    }

    public function store(CreateBankAccountRequest $request)
    {
        $this->bankAccountService->store($request->validated());

        return response()->json([
            'message' => trans('crud.created')
        ], 201);
    }

    public function show(string $id)
    {
        $bank_account = $this->bankAccountService->show($id);

        return response()->json(['data' => $bank_account], 200);
    }

    public function update(UpdateBankAccountRequest $request, string $id)
    {
        $this->bankAccountService->update($request->validated(), $id);

        return response()->json([
            'message' => trans('crud.updated')
        ], 200);
    }

    public function destroy(Request $request)
    {
        $ids = $request->input('ids');

        if (!is_array($ids)) {
            return response()->json(['error' => 'Invalid request format'], 422);
        }

        $this->bankAccountService->destroy($ids);

        return response()->json([
            'message' => trans('crud.deleted')
        ], 200);
    }
}
