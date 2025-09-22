<?php

namespace App\Models\Tenant\Accounting\Branches;

use App\Models\Tenant\Accounting\Accountants\JournalLineItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{

    protected $fillable = [
        'name_ar',
        'name_en',
        'phone',
        'commercial_number',
        'building_number',
        'street',
        'district',
        'city',
        'postal_code',
    ];

    public function journal_line_items(): HasMany
    {
        return $this->hasMany(JournalLineItem::class);
    }
}
