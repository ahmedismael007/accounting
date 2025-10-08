<?php

namespace App\Models\Tenant\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollPayment extends Model
{
    protected $fillable = [
        'account_id',
        'currency',
        'amount_paid',
        'exchange_rate',
        'amount_paid_dc',
        'date',
        'description',
        'branch_id',
        'project_id',
        'cost_center_id',
        'reference',
        'adjustment_amount',
        'adjustment_account',
    ];

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }
}
