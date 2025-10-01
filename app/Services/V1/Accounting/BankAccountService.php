<?php

namespace App\Services\V1\Accounting;

use App\Models\Tenant\Accounting\Accountants\Account;
use App\Repositories\V1\Accounting\BankAccountRepo;
use App\Services\V1\Common\AccountCodeGeneratorService;
use App\Services\V1\Common\QueryBuilderService;
use Exception;
use Illuminate\Http\Request;

class BankAccountService
{
    public function __construct(
        protected BankAccountRepo             $bankAccountRepo,
        protected QueryBuilderService         $queryBuilderService,
        protected AccountCodeGeneratorService $codeGenerator
    )
    {
    }

    public function index(Request $request)
    {
        $query = $this->bankAccountRepo->index();
        return $this->queryBuilderService->applyQuery($request, $query);
    }

    public function store(array $data)
    {
        $bank_account = $this->bankAccountRepo->store($data);

        $account_code = $this->codeGenerator->generate(3);

        Account::create([
            'account_code' => $account_code,
            'classification' => 'ASSET',
            'name' => $bank_account->name,
            'activity' => 'CASH',
            'parent_id' => 3,
            'show_in_expense_claims' => false,
            'bank_account_id' => $bank_account->id,
            'is_locked' => false,
            'lock_reason' => null,
            'is_system' => false,
            'is_payment_enabled' => true,
        ]);

        return $bank_account;
    }

    public function show(string $id)
    {
        return $this->bankAccountRepo->show($id);
    }

    public function update(array $data, string $id)
    {
        $bank_account = $this->bankAccountRepo->update($data, $id);

        // update associated account
        Account::where('bank_account_id', $id)->update([
            'name' => $data['name'] ?? $bank_account->name,
        ]);

        return $bank_account;
    }

    public function destroy(array $ids)
    {
        Account::whereIn('bank_account_id', $ids)->delete();
        return $this->bankAccountRepo->destroy($ids);
    }
}
