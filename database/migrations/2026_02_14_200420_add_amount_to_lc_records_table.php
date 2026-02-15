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
        Schema::table('l_c_records', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->after('expiry_date')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('l_c_records', function (Blueprint $table) {
            $table->dropColumn('amount');
        });
    }
};
