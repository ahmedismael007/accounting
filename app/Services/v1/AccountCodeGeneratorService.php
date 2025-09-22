<?php

namespace App\Services\v1;

use App\Models\Tenant\Accounting\Accountants\Account;
use Illuminate\Support\Str;

class AccountCodeGeneratorService
{
    public function generate(int $parentId): string
    {
        $parent = Account::findOrFail($parentId);
        $parentCode = $parent->account_code;

        $maxCode = Account::where('parent_id', $parentId)
            ->where('account_code', 'like', "{$parentCode}%")
            ->orderByDesc('account_code')
            ->value('account_code');

        if (!$maxCode) {
            return $parentCode . '1';
        }

        $suffix = (int) Str::after($maxCode, $parentCode);
        $nextCode = $parentCode . ($suffix + 1);

        return $nextCode;
    }
}
