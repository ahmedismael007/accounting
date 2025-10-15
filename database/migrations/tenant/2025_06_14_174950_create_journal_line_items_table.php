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
        Schema::create('journal_line_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('account_id')->nullable()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->text('created_by')->default('SYSTEM');
            $table->enum('type', ['manual', 'auto'])->default('manual');
            $table->text('description')->nullable();
            $table->string('currency');
            $table->decimal('exchange_rate', 15, 2)->default(1);
            $table->decimal('debit', 15, 2)->nullable()->default(0);
            $table->decimal('credit', 15, 2)->nullable()->default(0);
            $table->decimal('debit_dc', 15, 2)->nullable()->default(0);
            $table->decimal('credit_dc', 15, 2)->nullable()->default(0);
            $table->foreignId('tax_rate_id')->nullable()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('cost_center_id')->nullable()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_line_items');
    }
};
