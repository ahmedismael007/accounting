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
        Schema::create('journal_line_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_id')->constrained()->cascadeOnDelete();
            $table->foreignId('account_id')->nullable()->constrained()->nullOnDelete();
            $table->text('user');
            $table->enum('type', ['manual', 'auto'])->default('manual');
            $table->text('description');
            $table->string('currency');
            $table->decimal('exchange_rate', 15, 2)->default(1);
            $table->decimal('debit', 15, 2);
            $table->decimal('credit', 15, 2);
            $table->decimal('debit_dc', 15, 2);
            $table->decimal('credit_dc', 15, 2);
            $table->decimal('tax_rate_id', 6, 2)->default(0);
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('cost_center_id')->nullable()->constrained()->nullOnDelete();
            $table->string('source_type')->default('manual');
            $table->unsignedBigInteger('source_id')->nullable();
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
