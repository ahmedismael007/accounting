<?php

namespace App\Models\Tenant\Accounting\BankAccounts;

use App\Models\Tenant\Accounting\Accountants\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BankAccount extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'type',
        'currency',
    ];

    public function account(): HasOne
    {
        return $this->hasOne(Account::class);
    }
}
