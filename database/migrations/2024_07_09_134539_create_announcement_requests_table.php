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
        Schema::create('announcement_requests', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('announcement_id')
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            
            $table->string('requestable_type');

            $table->unsignedBigInteger('requestable_id');

            $table->string('body');

            $table->boolean('is_noted')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_requests');
    }
};
