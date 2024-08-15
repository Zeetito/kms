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
        Schema::create('account_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreignId('user_id')
                    ->nullable()
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('set null');

            $table->decimal('unit_cost', 10, 2)->default(0.00);

            $table->decimal('unit_number',6,2)->nullable();

            $table->decimal('discount', 8, 2)->default(0.00);

            $table->string('info')->nullable();
            
            $table->string('value');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_records');
    }
};
