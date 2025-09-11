<?php

use App\Providers\TenancyServiceProvider;
use Illuminate\Auth\AuthServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    TenancyServiceProvider::class,
    AuthServiceProvider::class,

];
