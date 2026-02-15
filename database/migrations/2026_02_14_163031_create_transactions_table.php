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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('transaction_code')->unique(); // Auto-generated
            $table->foreignId('buyer_id')->constrained('buyers');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->foreignId('origin_country_id')->nullable();
            $table->foreignId('destination_country_id')->nullable();
            $table->foreignId('structuring_country_id')->nullable();
            $table->string('product_category')->nullable();
            $table->string('hs_code')->nullable();
            $table->decimal('quantity', 15, 2)->default(0);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->foreignId('currency_id')->nullable(); // Assuming currency table or enum
            $table->decimal('margin_percentage', 5, 2)->default(0);
            $table->decimal('calculated_profit', 15, 2)->default(0);
            $table->string('payment_type'); // LC, SBLC, Advance
            $table->enum('status', [
                'LOI_RECEIVED',
                'LC_ISSUED',
                'PRODUCTION',
                'SHIPPED',
                'AT_PORT',
                'CLEARED',
                'PAYMENT_REALIZED',
                'CLOSED'
            ])->default('LOI_RECEIVED');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
