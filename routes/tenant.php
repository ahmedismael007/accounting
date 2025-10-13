<?php

declare(strict_types=1);

use App\Http\Controllers\Central\Tenant\TenantController;
use App\Http\Controllers\Central\User\UserController;
use App\Http\Controllers\Tenant\Accounting\Accountant\AccountController;
use App\Http\Controllers\Tenant\Accounting\Accountant\JournalController;
use App\Http\Controllers\Tenant\Accounting\Accountant\TaxRateController;
use App\Http\Controllers\Tenant\Accounting\BankAccount\BankAccountController;
use App\Http\Controllers\Tenant\Accounting\Branch\BranchController;
use App\Http\Controllers\Tenant\Accounting\CostCenter\CostCenterController;
use App\Http\Controllers\Tenant\Accounting\Customer\CustomerController;
use App\Http\Controllers\Tenant\Accounting\FixedAssets\FixedAssetController;
use App\Http\Controllers\Tenant\Accounting\Projects\ProjectController;
use App\Http\Controllers\Tenant\Accounting\Sales\QuoteController;
use App\Http\Controllers\Tenant\Inventory\InventoryAdjustmentController;
use App\Http\Controllers\Tenant\Inventory\ItemController;
use App\Http\Controllers\Tenant\Inventory\WarehouseController;
use App\Http\Controllers\Tenant\Payroll\EmployeeController;
use App\Http\Controllers\Tenant\Payroll\PayrollController;
use App\Http\Controllers\Tenant\Payroll\PayrollPaymentController;
use App\Http\Controllers\Tenant\Payroll\PayRunController;
use App\Http\Middleware\InitializeTenantFromHeader;
use App\Http\Middleware\SetLocaleFromHeader;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1')->middleware([InitializeTenantFromHeader::class, SetLocaleFromHeader::class])->group(function () {
    // Organization
    Route::apiResource('tenants', TenantController::class);
    Route::post('tenants/logo', [TenantController::class, 'update_logo']);
    Route::get('user-tenants', [UserController::class, 'user_tenants']);

    // Accounting
    Route::delete('chart-of-accounts', [AccountController::class, 'destroy']);
    Route::apiResource('chart-of-accounts', AccountController::class);

    Route::delete('tax-rates', [TaxRateController::class, 'destroy']);
    Route::apiResource('tax-rates', TaxRateController::class);

    Route::delete('branches', [BranchController::class, 'destroy']);
    Route::apiResource('branches', BranchController::class);

    Route::delete('projects', [ProjectController::class, 'destroy']);
    Route::apiResource('projects', ProjectController::class);

    Route::delete('bank-accounts', [BankAccountController::class, 'destroy']);
    Route::apiResource('bank-accounts', BankAccountController::class);

    Route::delete('cost-centers', [CostCenterController::class, 'destroy']);
    Route::apiResource('cost-centers', CostCenterController::class);

    Route::delete('fixed-assets', [FixedAssetController::class, 'destroy']);
    Route::apiResource('fixed-assets', FixedAssetController::class);

    Route::delete('customers', [CustomerController::class, 'destroy']);
    Route::apiResource('customers', CustomerController::class);

    Route::delete('journals', [JournalController::class, 'destroy']);
    Route::apiResource('journals', JournalController::class);

    Route::delete('quotes-and-proformas', [QuoteController::class, 'destroy']);
    Route::apiResource('quotes-and-proformas', QuoteController::class);

    // Inventory Management
    Route::delete('items', [ItemController::class, 'destroy']);
    Route::apiResource('items', ItemController::class);

    Route::delete('inventory-adjustments', [InventoryAdjustmentController::class, 'destroy']);
    Route::apiResource('inventory-adjustments', InventoryAdjustmentController::class);

    Route::delete('warehouses', [WarehouseController::class, 'destroy']);
    Route::apiResource('warehouses', WarehouseController::class);

    // Payroll
    Route::delete('employees', [EmployeeController::class, 'destroy']);
    Route::apiResource('employees', EmployeeController::class);

    Route::delete('payrolls', [PayrollController::class, 'destroy']);
    Route::apiResource('payrolls', PayrollController::class);

    Route::delete('payruns', [PayRunController::class, 'destroy']);
    Route::apiResource('payruns', PayRunController::class);

    Route::delete('payroll-payments', [PayrollPaymentController::class, 'destroy']);
    Route::apiResource('payroll-payments', PayrollPaymentController::class);

});
