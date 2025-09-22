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
        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_number')->unique();
            $table->string('name');
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_cost', 15, 2)->nullable();
            $table->decimal('salvage_value', 15, 2)->nullable();
            $table->integer('life_in_months')->nullable();
            $table->date('depreciation_start_month')->nullable();
            $table->decimal('current_book_value', 15, 2)->nullable();
            $table->date('depreciated_until')->nullable();
            $table->string('fixed_asset_account')->nullable();
            $table->string('depreciation_account')->nullable();
            $table->string('accumulated_depreciation_account')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('cost_center_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_assets');
    }
};
