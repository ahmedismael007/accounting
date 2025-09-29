<?php

namespace App\Repositories\v1\Accounting;

use App\Models\Tenant\Accounting\Accountants\Account;

class AccountRepo
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        return Account::with('children')->whereNull('parent_id');
    }

    public function store(array $data)
    {
        return Account::create($data);
    }

    public function update(string $id, array $data)
    {
        $account = $this->show($id);

        $account->update($data);

        return $account;
    }

    public function show(string $id)
    {
        return Account::with('children')->whereNull('parent_id')->findOrFail($id);
    }

    public function destroy()
    {
    }
}
