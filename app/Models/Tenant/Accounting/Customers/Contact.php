<?php

namespace App\Models\Tenant\Accounting\Customers;

use App\Models\Tenant\Accounting\Accountants\JournalLineItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'country',
        'is_vat_registered',
        'tax_number',
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
