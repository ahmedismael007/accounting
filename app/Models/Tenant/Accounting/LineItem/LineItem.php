<?php

    namespace App\Models\Tenant\Accounting\LineItem;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\MorphTo;

    class LineItem extends Model
    {
        protected $fillable = [
            'description', 'account_id', 'quantity', 'price', 'discount',
            'product_id', 'cost_center_id', 'tax_rate_id'
        ];

        public function lineItemable(): MorphTo
        {
            return $this->morphTo();
        }
    }
