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
        Schema::create('pay_runs', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->string('currency', 4);
            $table->text('description')->nullable();
            $table->decimal('amount_to_pay', 15, 2)->default(0);
            $table->decimal('exchange_rate', 15, 2)->default(1);
            $table->decimal('amount_to_pay_dc', 15, 2)->default(0);
            $table->decimal('payments', 15, 2)->default(0);
            $table->decimal('payslip_amount', 15, 2)->default(0);

            $table->foreignId('employee_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->noActionOnDelete();
            $table->foreignId('account_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->noActionOnDelete();

            $table->foreignId('cost_center_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->noActionOnDelete();

            $table->foreignId('project_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->noActionOnDelete();

            $table->foreignId('branch_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->noActionOnDelete();

            $table->enum('status', ['DRAFT', 'APPROVED', 'PAID', 'PARTIALLY_PAID'])->default('DRAFT');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_runs');
    }
};
