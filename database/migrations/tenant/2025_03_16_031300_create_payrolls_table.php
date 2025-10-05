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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('account_id');
            $table->decimal('amount', 15, 2)->default(0);
            $table->unsignedBigInteger('cost_center_id')->nullable();
            $table->decimal('total', 15, 2)->default(0);
            $table->string('currency', 10);
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
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
