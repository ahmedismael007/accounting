<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('invoice_number');
            $table->string('currency');
            $table->date('date');
            $table->date('due_date');
            $table->string('purchase_order');
            $table->string('reference');
            $table->foreignId('project_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('tax_amount_type', ['tax_included', 'tax_excluded'])->default('tax_included');
            $table->text('notes');
            $table->decimal('subtotal', 20, 2);
            $table->decimal('discount', 20, 2);
            $table->decimal('vat', 20, 2);
            $table->decimal('total', 20, 2);
            $table->decimal('net_due', 20, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
