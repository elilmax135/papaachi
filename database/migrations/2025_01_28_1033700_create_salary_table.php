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
        Schema::create('salary', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->unsignedBigInteger('staff_id');
            $table->integer('payment');
            $table->integer('paid')->default('0');
            $table->date('payment_date')->nullable();
            $table->unsignedBigInteger('sells_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('staff_id')->references('id')->on('staffs')->onDelete('cascade');
            $table->foreign('sells_id')->references('id')->on('sells')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary');
    }
};
