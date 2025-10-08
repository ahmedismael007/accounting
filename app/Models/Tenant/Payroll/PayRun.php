<?php

namespace App\Models\Tenant\Payroll;

use App\Models\Tenant\Accounting\Accountants\Account;
use App\Models\Tenant\Accounting\Branches\Branch;
use App\Models\Tenant\Accounting\CostCenter\CostCenter;
use App\Models\Tenant\Accounting\Projects\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayRun extends Model
{
    protected $fillable = [
        'status',
        'start_date',
        'employee_id',
        'currency',
        'payslip_amount',
        'payments',
        'description',
        'account_id',
        'amount_to_pay',
        'exchange_rate',
        'amount_to_pay_dc',
        'cost_center_id',
        'project_id',
        'branch_id',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function cost_center(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
