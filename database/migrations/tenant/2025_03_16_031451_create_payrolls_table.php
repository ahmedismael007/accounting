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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnUpdate()->noActionOnDelete();
            $table->string('description')->nullable();
            $table->foreignId('account_id');
            $table->decimal('amount', 15, 2)->default(0);
            $table->foreignId('cost_center_id')->nullable()->constrained()->cascadeOnUpdate()->noActionOnDelete();
            $table->string('currency', 10);
            $table->foreignId('project_id')->nullable()->constrained()->cascadeOnUpdate()->noActionOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->cascadeOnUpdate()->noActionOnDelete();
            $table->boolean('include_in_payrun')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
