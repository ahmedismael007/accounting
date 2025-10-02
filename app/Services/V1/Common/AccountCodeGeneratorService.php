<?php

namespace App\Services\V1\Common;

use App\Models\Tenant\Accounting\Accountants\Account;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class AccountCodeGeneratorService
{
    public function generate(int $parentId): string
    {
        $parent = Account::find($parentId);
        if (!$parent) {
            throw new InvalidArgumentException("Parent account with ID {$parentId} not found.");
        }

        $parentCode = $parent->account_code;
        $pos = strlen($parentCode) + 1;

        $maxSuffix = DB::table('accounts')
            ->where('parent_id', $parentId)
            ->where('account_code', 'like', $parentCode . '%')
            ->selectRaw("MAX(CAST(SUBSTRING(account_code, {$pos}) AS UNSIGNED)) as max_suffix")
            ->lockForUpdate()
            ->value('max_suffix');

        $nextSuffix = $maxSuffix === null ? 1 : ((int)$maxSuffix + 1);
        $candidate = $parentCode . $nextSuffix;

        while (DB::table('accounts')->where('account_code', $candidate)->exists()) {
            $nextSuffix++;
            $candidate = $parentCode . $nextSuffix;
        }

        return $candidate;
    }
}
