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
        Schema::create('user_residences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreignId('residence_id')
                    ->nullable()
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('set null');

            $table->string('floor')->nullable();

            $table->string('room')->nullable();
            
            $table->string('block')->nullable();
            
            $table->string('custom_name')->nullable();

            $table->string('custom_description')->nullable();

            $table->foreignId('academic_year_id') 
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('cascade');


            $table->timestamps();
                    
            // unique constraint
            $table->unique(['user_id', 'academic_year_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
public function down(): void
    {
        Schema::dropIfExists('user_residences');
    }
};
