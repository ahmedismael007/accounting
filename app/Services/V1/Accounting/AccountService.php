<?php

namespace App\Services\V1\Accounting;

use App\Models\Tenant\Accounting\BankAccounts\BankAccount;
use App\Repositories\v1\Accounting\AccountRepo;
use App\Services\V1\Common\AccountCodeGeneratorService;
use App\Services\V1\Common\QueryBuilderService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
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

    public function index(Request $request): Collection
    {
        $query = $this->repo->index();

        return $this->queryBuilderService->applyQuery($request, $query)->get();
    }

    public function store(array $data): Model
    {
        $data['account_code'] = $this->codeGenerator->generate($data['parent_id'] ?? null);

        if (!empty($data['is_bank']) && $data['is_bank'] == true) {
            $bank_account = BankAccount::create([
                'name' => $data['name'],
                'type' => 'BANK_ACCOUNT',
                'currency' => 'SAR',
            ]);

            $data['bank_account_id'] = $bank_account->id;
        }

        return $this->repo->store($data);
    }

    public function update($id, array $data): ?Model
    {
        $account = $this->repo->show($id);

        if (!$account || $account->is_system) {
            return null;
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
                return false;
            }

            if ($account->bank_account_id) {
                BankAccount::where('id', $account->bank_account_id)->delete();
            }
        }

        return $this->repo->destroy($ids);
    }
}
