<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable();
            $table->string('name');
            $table->text('description')->nullable();

            $table->decimal('unit_cost', 15, 4)->nullable();
            $table->decimal('unit_price', 15, 4)->nullable();

            $table->foreignId('expense_account')->nullable()->constrained('accounts')->nullOnDelete();
            $table->foreignId('revenue_account')->nullable()->constrained('accounts')->nullOnDelete();

            $table->foreignId('purchase_tax_rate', 5, 2)->nullable()->constrained('tax_rates')->nullOnDelete();
            $table->foreignId('revenue_tax_rate', 5, 2)->nullable()->constrained('tax_rates')->nullOnDelete();

            $table->decimal('quantity', 15, 4)->nullable();
            $table->decimal('avg_unit_cost', 15, 4)->nullable();
            $table->decimal('inventory_value', 15, 4)->nullable();

            $table->string('measurement_unit', 50)->nullable();

            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->cascadeOnUpdate()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
