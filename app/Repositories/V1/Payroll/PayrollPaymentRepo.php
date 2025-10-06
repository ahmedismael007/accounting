<?php

namespace App\Repositories\V1\Payroll;

use App\Models\Tenant\Payroll\PayrollPayment;
use App\Repositories\V1\Common\BaseRepo;

class PayrollPaymentRepo extends BaseRepo
{
    /**
     * Create a new class instance.
     */
    public function __construct(PayrollPayment $model)
    {
        parent::__construct($model);
    }
}
