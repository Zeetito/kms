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
        Schema::create('user_programs', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id');

            $table->foreignId('program_id')->nullable()->constrained()->onDelete('set null');

            $table->string('custom_name')->nullable();

            $table->bigInteger('custom_college_id')->nullable();

            $table->integer("custom_span")->nullable();

            $table->integer('year')->nullable();

            $table->foreignId('academic_year_id');

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
        Schema::dropIfExists('user_programs');
    }
};
