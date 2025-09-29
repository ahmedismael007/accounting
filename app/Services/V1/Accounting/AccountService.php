<?php

namespace App\Services\V1\Accounting;

use App\Repositories\v1\Accounting\AccountRepo;

class AccountService
{
    /**
     * Create a new class instance.
     */
    public function __construct(private AccountRepo $repo)
    {
    }

}

