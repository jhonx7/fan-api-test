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
        Schema::create('epresences', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->enum('type',['IN', 'OUT']);
            $table->boolean('is_approve')->default(false);
            $table->timestamp('waktu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epresences');
    }
};
