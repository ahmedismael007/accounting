<?php

namespace App\Models\Tenant\Accounting\Discount;

use App\Models\Tenant\Accounting\Accountants\Account;
use App\Models\Tenant\Accounting\Accountants\TaxRate;
use App\Models\Tenant\Accounting\CostCenter\CostCenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Discount extends Model
{
    protected $fillable = [
        'amount',
        'account_id',
        'tax_rate_id',
        'cost_center_id',
    ];

    public function discountable(): MorphTo
    {
        return $this->morphTo();
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class);
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }
}
