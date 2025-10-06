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
        Schema::create('payroll_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->noActionOnDelete();

            $table->string('currency', 10);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->date('date');

            $table->text('description')->nullable();
            $table->string('reference', 100)->nullable();

            $table->foreignId('branch_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->noActionOnDelete();

            $table->foreignId('project_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->noActionOnDelete();

            $table->foreignId('cost_center_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->noActionOnDelete();

            $table->decimal('adjustment_amount', 15, 2)->nullable()->default(0);

            $table->foreignId('adjustment_account')
                ->nullable()
                ->constrained('accounts')
                ->cascadeOnUpdate()
                ->noActionOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_payments');
    }
};
