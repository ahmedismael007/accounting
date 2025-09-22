<?php

namespace App\Models\Tenant\Accounting\FixedAssets;

use Illuminate\Database\Eloquent\Model;

class FixedAsset extends Model
{
    protected $fillable = [
        'asset_number',
        'name',
        'purchase_date',
        'purchase_cost',
        'salvage_value',
        'life_in_months',
        'depreciation_start_month',
        'current_book_value',
        'depreciated_until',
        'fixed_asset_account',
        'depreciation_account',
        'accumulated_depreciation_account',
        'notes',
        'cost_center_id',
        'project_id',
    ];
}
