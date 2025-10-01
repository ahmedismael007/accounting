<?php

namespace App\Models\Tenant\Accounting\Accountants;

use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{

    protected $fillable = [
        'name',
        'tax_type',
        'tax_rate',
        'description',
        'is_system'
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'name' => 'array'
    ];
}
