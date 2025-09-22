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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_code')->unique()->nullable();
            $table->string('classification')->nullable();
            $table->json('name')->nullable();
            $table->string('activity');
            $table->foreignId('parent_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->boolean('show_in_expense_claims')->default(true);
            $table->foreignId('bank_account_id')->nullable()->constrained('bank_accounts')->cascadeOnUpdate()->cascadeOnDelete();
            $table->boolean('is_locked')->default(false);
            $table->string('lock_reason')->nullable();
            $table->boolean('is_system')->default(false);
            $table->boolean('is_payment_enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
