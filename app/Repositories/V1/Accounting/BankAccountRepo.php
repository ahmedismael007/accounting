<?php

namespace App\Repositories\V1\Accounting;

use App\Models\Tenant\Accounting\BankAccounts\BankAccount;
use Exception;
use function trans;

class BankAccountRepo
{
    public function index()
    {
        return BankAccount::with('account')->latest();
    }

    public function store(array $data): BankAccount
    {
        return BankAccount::create($data);
    }

    public function show(string $id): BankAccount
    {
        return BankAccount::with('account')->findOrFail($id);
    }

    public function update(array $data, string $id): BankAccount
    {
        $bank_account = $this->show($id);

        $associated_account = $bank_account->account;

        if ($associated_account['is_system']) {
            throw new Exception(trans('accounting.bank_account_not_editable'));
        }

        $bank_account->update($data);

        return $bank_account;
    }

    public function destroy(array $ids)
    {
        foreach ($ids as $id) {
            $bank_account = $this->show($id);

            $associated_account = $bank_account->account;

            if ($associated_account && $associated_account['is_system']) {
                throw new Exception(trans('accounting.bank_account_not_editable'));
            }
        }
        return BankAccount::destroy($ids);
    }
}
