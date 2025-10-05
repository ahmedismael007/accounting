<?php

namespace App\Models\Tenant\Payroll;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id',
        'description',
        'account_id',
        'amount',
        'cost_center_id',
        'total',
        'currency',
        'project_id',
        'branch_id',
        'include_in_payrun',
     ];
}
