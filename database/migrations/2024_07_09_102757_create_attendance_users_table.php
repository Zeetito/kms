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
        Schema::create('attendance_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreignId('user_id')
                    ->nullable()
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('set null');
                   
            $table->boolean('is_present')->default(1);

            $table->json('guest_details')->nullable();

            $table->string('info')->nullable();

            $table->unsignedBigInteger('marked_by')->nullable();

            $table->foreign('marked_by')->references('id')->on('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_users');
    }
};
