<?php

namespace App\Repositories\V1\Payroll;

use App\Models\Tenant\Payroll\Payroll;
use App\Repositories\V1\Common\BaseRepo;

class PayrollRepo extends BaseRepo
{
    /**
     * Create a new class instance.
     */
    public function __construct(Payroll $model)
    {
        parent::__construct($model);
    }
}
