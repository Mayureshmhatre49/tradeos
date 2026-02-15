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
        Schema::create('system_activities', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // high, normal, primary, etc.
            $table->string('title');
            $table->text('description');
            $table->string('user_identifier')->nullable();
            $table->nullableMorphs('subject'); // To link to transactions, etc.
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_activities');
    }
};
