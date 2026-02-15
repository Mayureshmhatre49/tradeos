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
        Schema::create('l_c_records', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('transaction_id')->constrained('transactions');
            $table->string('lc_number')->unique();
            $table->string('issuing_bank');
            $table->string('confirming_bank')->nullable();
            $table->date('expiry_date');
            $table->date('shipment_deadline');
            $table->text('discrepancy_notes')->nullable();
            $table->enum('payment_status', ['PENDING', 'PAID', 'PARTIAL', 'OVERDUE'])->default('PENDING');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('l_c_records');
    }
};
