<?php

namespace App\Models\Tenant\Inventory;

use App\Models\Tenant\Accounting\Accountants\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryAdjustment extends Model {
    protected $fillable = [
        'status',
        'reference',
        'date',
        'currency',
        'warehouse_id',
        'item_id',
        'line_item_description',
        'qty',
        'inventory_value',
        'account_id',
        'total_adjustment_amount',
        'adjustment_id',
     ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
