<?php

namespace App\Services\V1\Accounting;

use App\Models\Tenant\Accounting\BankAccounts\BankAccount;
use App\Repositories\v1\Accounting\AccountRepo;
use App\Services\V1\Common\AccountCodeGeneratorService;
use App\Services\V1\Common\QueryBuilderService;
use Illuminate\Http\Request;

class AccountService
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected AccountRepo $repo, protected QueryBuilderService $queryBuilderService, protected AccountCodeGeneratorService $codeGenerator)
    {
    }

    public function index(Request $request)
    {
        $query = $this->repo->index;

        return $this->queryBuilderService->applyQuery($request, $query);
    }

    public function store(array $data)
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

    public function update($id, array $data)
    {
        $account = $this->repo->show($id);

        if (!$account) {
            return ['status' => false, 'code' => 404, 'message' => 'Account not found.'];
        }

        if ($account->is_system) {
            return ['status' => false, 'code' => 422, 'message' => trans('accounting.account_not_editable')];
        }

        if (isset($data['parent_id']) && $data['parent_id'] != $account->parent_id) {
            $data['account_code'] = $this->codeGenerator->generate($data['parent_id']);
        }

        $this->repo->update($account, $data);

        if ($account->bank_account_id) {
            BankAccount::where('id', $account->bank_account_id)->update([
                'name_ar' => $account['name_ar'],
                'name_en' => $account['name_en'],
            ]);
        }

        return ['status' => true, 'code' => 200, 'message' => 'تم تعديل بيانات الحساب بنجاح.'];
    }

    public function destroy(array $ids)
    {
        foreach ($ids as $id) {
            $account = $this->repo->findOrFail($id);

            if ($account->is_system) {
                return ['status' => false, 'code' => 422, 'message' => trans('accounting.account_not_deletable')];
            }

            if ($account->bank_account_id) {
                BankAccount::where('id', $account->bank_account_id)->delete();
            }
        }

        $this->repo->destroy($ids);

        return ['status' => true, 'code' => 200, 'message' => 'تم حذف الحساب بنجاح.'];
    }

}

