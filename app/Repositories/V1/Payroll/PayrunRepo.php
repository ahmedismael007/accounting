<?php

namespace App\Repositories\V1\Payroll;

use App\Models\Tenant\Payroll\PayRun;
use App\Repositories\V1\Common\BaseRepo;

class PayrunRepo extends BaseRepo
{
    /**
     * Create a new class instance.
     */
    public function __construct(PayRun $model)
    {
        parent::__construct($model);
    }
}
