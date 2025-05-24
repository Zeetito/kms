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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('othername')->nullable();
            $table->char('gender', 1)->nullable();
            $table->boolean('is_member')->nullable();
            $table->boolean('is_baptised')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('active_contact')->nullable();
            $table->json('contacts')->nullable();
            $table->boolean('is_alumni')->default(0);
            $table->boolean('is_student')->nullable();
            $table->integer('is_worker')->default(0);
            $table->boolean('is_knust_affiliate')->nullable();
            $table->json('local_congregation')->nullable();
            $table->integer('is_active')->default(0); // 0->inactive , 1 ->active, 2->deactivated
            $table->date('dob')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
