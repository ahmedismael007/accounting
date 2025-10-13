<?php

namespace App\Models\Tenant\Accounting\Sales;

use App\Models\Tenant\Accounting\Customer\Customer;
use App\Models\Tenant\Accounting\Projects\Project;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Quote extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'quote_number',
        'customer_id',
        'currency',
        'date',
        'purchase_order',
        'project_id',
        'reference',
        'notes',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('quotes');
    }
}
