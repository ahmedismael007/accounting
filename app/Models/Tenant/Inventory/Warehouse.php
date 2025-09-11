<?php

namespace App\Models\Tenant\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'building_number',
        'street',
        'district',
        'city',
        'postal_code',
        'account_id'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
