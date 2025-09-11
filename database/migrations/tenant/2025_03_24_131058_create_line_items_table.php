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
                $table->foreignId('account_id')->constrained();
                $table->integer('quantity');
                $table->decimal('price', 12, 2);
                $table->integer('discount')->nullable();
                $table->foreignId('product_id')->nullable()->constrained();
                $table->foreignId('cost_center_id')->nullable()->constrained();
                $table->foreignId('tax_rate_id')->nullable()->constrained();
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
