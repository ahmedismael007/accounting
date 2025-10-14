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
        Schema::create('line_items', function (Blueprint $table) {
            $table->id();
            $table->morphs('line_itemable');
            $table->text('description');
            $table->decimal('quantity', 15, 2);
            $table->decimal('price', 15, 2);
            $table->foreignId('item_id')->nullable()->constrained('items')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('tax_rate_id')->nullable()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('cost_center_id')->nullable()->constrained('cost_centers')->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('discount_percent', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('line_items');
    }
};
