<?php

namespace App\Models\Tenant\Accounting\CostCenter;

use App\Models\Tenant\Accounting\Accountants\JournalLineItem;
use App\Models\Tenant\Payroll\Payroll;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CostCenter extends Model
{
    protected $fillable = ['name'];

    protected $casts = [
        'name' => 'array'
    ];

    public function journal_line_items(): HasMany
    {
        return $this->hasMany(JournalLineItem::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }
}
