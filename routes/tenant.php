<?php

declare(strict_types=1);

use App\Http\Controllers\Central\Tenant\TenantController;
use App\Http\Controllers\Central\User\UserController;
use App\Http\Controllers\Tenant\Accounting\Accountants\AccountController;
use App\Http\Controllers\Tenant\Accounting\Accountants\JournalController;
use App\Http\Controllers\Tenant\Accounting\Accountants\TaxRateController;
use App\Http\Controllers\Tenant\Accounting\BankAccounts\BankAccountController;
use App\Http\Controllers\Tenant\Accounting\Branches\BranchController;
use App\Http\Controllers\Tenant\Accounting\CostCenter\CostCenterController;
use App\Http\Controllers\Tenant\Accounting\Customers\ContactController;
use App\Http\Controllers\Tenant\Accounting\FixedAssets\FixedAssetController;
use App\Http\Controllers\Tenant\Accounting\Projects\ProjectController;
use App\Http\Controllers\Tenant\Inventory\InventoryAdjustmentController;
use App\Http\Controllers\Tenant\Inventory\ProductController;
use App\Http\Controllers\Tenant\Inventory\WarehouseController;
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
    Route::apiResource('tax-rates', TaxRateController::class);
    Route::apiResource('branches', BranchController::class);
    Route::apiResource('cost-centers', CostCenterController::class);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('fixed-assets', FixedAssetController::class);
    Route::apiResource('bank-accounts', BankAccountController::class);
    Route::apiResource('contacts', ContactController::class);
    Route::apiResource('journals', JournalController::class);

    // Inventory Management
    Route::apiResource('products', ProductController::class);
    Route::apiResource('adjustments', InventoryAdjustmentController::class);
    Route::apiResource('warehouses', WarehouseController::class);
});
