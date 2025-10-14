<?php

namespace App\Models\Tenant\Accounting\Accountants;

use App\Models\Tenant\Accounting\BankAccounts\BankAccount;
use App\Models\Tenant\Accounting\Discount\Discount;
use App\Models\Tenant\Inventory\InventoryAdjustment;
use App\Models\Tenant\Payroll\Payroll;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Account extends Model
{

    protected $fillable = [
        'account_code',
        'classification',
        'name',
        'activity',
        'parent_id',
        'show_in_expense_claims',
        'bank_account_id',
        'is_locked',
        'lock_reason',
        'is_system',
        'is_payment_enabled',
    ];

    protected $casts = [
        'name' => 'array',
        'show_in_expense_claims' => 'boolean',
        'is_locked' => 'boolean',
        'is_system' => 'boolean',
        'is_payment_enabled' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id')->with('children');
    }

    public function bank_account(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function journal_line_items(): HasMany
    {
        return $this->hasMany(JournalLineItem::class);
    }

    public function inventory_adjustments(): HasMany
    {
        return $this->hasMany(InventoryAdjustment::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }
}
