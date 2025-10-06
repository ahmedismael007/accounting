<?php

namespace App\Repositories\V1\Payroll;

use App\Models\Tenant\Payroll\Employee;
use App\Repositories\V1\Common\BaseRepo;

class EmployeeRepo extends BaseRepo
{
    /**
     * Create a new class instance.
     */
    public function __construct(Employee $model)
    {
        parent::__construct($model);
    }
    
}
