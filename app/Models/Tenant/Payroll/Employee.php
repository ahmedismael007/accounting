<?php

namespace App\Models\Tenant\Payroll;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'email',
        'user_id',
        'country',
        'city',
        'address',
    ];
 }
