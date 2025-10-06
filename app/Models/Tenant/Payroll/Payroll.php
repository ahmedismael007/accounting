<?php

namespace App\Models\Tenant\Payroll;

use App\Models\Tenant\Accounting\Accountants\Account;
use App\Models\Tenant\Accounting\Branches\Branch;
use App\Models\Tenant\Accounting\CostCenter\CostCenter;
use App\Models\Tenant\Accounting\Projects\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id',
        'description',
        'account_id',
        'amount',
        'cost_center_id',
        'currency',
        'project_id',
        'branch_id',
        'include_in_payrun',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

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
