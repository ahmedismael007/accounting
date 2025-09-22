<?php

namespace App\Models\Tenant\Accounting\Accountants;

use App\Models\Tenant\Accounting\Branches\Branch;
use App\Models\Tenant\Accounting\CostCenter\CostCenter;
use App\Models\Tenant\Accounting\Customers\Contact;
use App\Models\Tenant\Accounting\Projects\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalLineItem extends Model
{
    protected $fillable = [
        'journal_id',
        'account_id',
        'user',
        'type',
        'description',
        'currency',
        'exchange_rate',
        'debit',
        'credit',
        'debit_dc',
        'credit_dc',
        'tax_rate_id',
        'contact_id',
        'project_id',
        'branch_id',
        'cost_center_id',
        'source_type',
        'source_id',
    ];

    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class);
    }
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
    public function cost_center(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }
}
