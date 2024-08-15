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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('college_id')->nullable()
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('set null');

            $table->foreignId('faculty_id')->nullable()
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('set null');

            $table->foreignId('department_id')->nullable()
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('set null');

            $table->string('type');
            $table->integer('span')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
