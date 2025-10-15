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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->morphs('discountable');
            $table->decimal('amount', 15, 2)->default(0);
            $table->foreignId('account_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('tax_rate_id')->nullable()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('cost_center_id')->nullable()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
