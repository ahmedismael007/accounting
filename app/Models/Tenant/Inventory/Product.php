<?php

namespace App\Models\Tenant\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'sku',
        'name',
        'description',
        'unit_cost',
        'unit_price',
        'expense_account',
        'revenue_account',
        'purchase_tax_rate',
        'revenue_tax_rate',
        'quantity',
        'avg_unit_cost',
        'inventory_value',
        'measurement_unit',
        'warehouse_id'
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
