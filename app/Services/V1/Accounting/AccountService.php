<?php

namespace App\Services\V1\Accounting;

use App\Models\Tenant\Accounting\BankAccounts\BankAccount;
use App\Repositories\V1\Accounting\AccountRepo;
use App\Services\V1\Common\AccountCodeGeneratorService;
use App\Services\V1\Common\QueryBuilderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class AccountService
{
    public function __construct(
        protected AccountRepo                 $repo,
        protected QueryBuilderService         $queryBuilderService,
        protected AccountCodeGeneratorService $codeGenerator
    )
    {
    }

    public function index(Request $request)
    {
        $query = $this->repo->index();

        return $this->queryBuilderService->applyQuery($request, $query);
    }

    public function store(array $data): Model
    {
        if ($data['parent_id']) {
            $data['account_code'] = $this->codeGenerator->generate($data['parent_id'] ?? null);
        }

        if (!empty($data['is_bank']) && $data['is_bank'] == true) {
            $tenant = tenant();
            $bank_account = BankAccount::create([
                'name' => $data['name'],
                'type' => 'BANK_ACCOUNT',
                'currency' => $tenant['currency'] ?? 'SAR',
            ]);

            $data['bank_account_id'] = $bank_account->id;
        }

        return $this->repo->store($data);
    }

    public function update($id, array $data): ?Model
    {
        $account = $this->repo->show($id);

        if (!$account || $account->is_system) {
            throw new Exception(trans('accounting.account_not_editable'));
        }

        if (isset($data['parent_id']) && $data['parent_id'] != $account->parent_id) {
            $data['account_code'] = $this->codeGenerator->generate($data['parent_id']);
        }

        if ($account->bank_account_id) {
            BankAccount::where('id', $account->bank_account_id)->update([
                'name' => $data['name'] ?? $account->name,
            ]);
        }

        return $this->repo->update($id, $data);
    }

    public function show($id): ?Model
    {
        return $this->repo->show($id);
    }

    public function destroy(array $ids): bool
    {
        foreach ($ids as $id) {
            $account = $this->repo->show($id);

            if ($account->is_system) {
                throw new Exception(trans('accounting.account_not_deletable'));
            }

            if ($account->bank_account_id) {
                BankAccount::where('id', $account->bank_account_id)->delete();
            }
        }

        return $this->repo->destroy($ids);
    }
}
