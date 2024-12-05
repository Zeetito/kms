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
        Schema::create('seens', function (Blueprint $table) {
            $table->id();
            $table->morphs('seenable');
            $table->foreignId('user_id');
            $table->date('seen_at')->nullable();
            $table->timestamps();

            $table->unique(['seenable_id', 'seenable_type', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seens');
    }
};
