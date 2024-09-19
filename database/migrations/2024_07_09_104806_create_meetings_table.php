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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('meeting_type_id')
                    ->nullable()
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('set null');

            $table->string('program_name')->nullable();

            $table->boolean('is_special')->default(0);

            $table->boolean('allows_question')->default(0);

            $table->string('description')->nullable();

            $table->date('start_date');

            $table->date('end_date')->nullable();

            $table->time('start_time')->nullable();
            
            $table->time('end_time')->nullable();

            $table->string('venue')->nullable();

            $table->text('location')->nullable();

            $table->foreignId('semester_id')->constrained() ;

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
