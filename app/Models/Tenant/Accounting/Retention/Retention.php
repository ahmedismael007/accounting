<?php

namespace App\Models\Tenant\Accounting\Retention;

use App\Models\Tenant\Accounting\Accountants\Account;
use Illuminate\Database\Eloquent\Model;

class Retention extends Model
{
    protected $fillable = [
        'account_id',
        'amount',
    ];

    public function retentionable()
    {
        return $this->morphTo();
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
