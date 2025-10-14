<?php

namespace App\Models\Tenant\Accounting\Sales;

use App\Models\Tenant\Accounting\Customer\Customer;
use App\Models\Tenant\Accounting\Discount\Discount;
use App\Models\Tenant\Accounting\LineItem\LineItem;
use App\Models\Tenant\Accounting\Projects\Project;
use App\Models\Tenant\Accounting\Retention\Retention;
use App\Models\Tenant\Inventory\Warehouse;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'customer_id',
        'invoice_number',
        'currency',
        'date',
        'due_date',
        'purchase_order',
        'reference',
        'project_id',
        'warehouse_id',
        'tax_amount_type',
        'notes',
        'subtotal',
        'discount',
        'vat',
        'total',
        'net_due',
    ];

    /** Relationships */

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function lineItems()
    {
        return $this->morphMany(LineItem::class, 'line_itemable');
    }

    public function discount()
    {
        return $this->morphOne(Discount::class, 'discountable');
    }

    public function retention()
    {
        return $this->morphOne(Retention::class, 'retentionable');
    }
}
