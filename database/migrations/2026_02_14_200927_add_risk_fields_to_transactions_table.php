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
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('risk_level', ['low', 'medium', 'high'])->default('low')->after('status');
            $table->decimal('risk_score', 4, 2)->default(0)->after('risk_level');
            $table->text('risk_reason')->nullable()->after('risk_score');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['risk_level', 'risk_score', 'risk_reason']);
        });
    }
};
