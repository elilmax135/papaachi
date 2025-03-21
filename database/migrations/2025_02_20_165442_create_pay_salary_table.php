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
        Schema::create('pay_salary', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->unsignedBigInteger('staff_id');
            $table->decimal('payment',15,2);
            $table->decimal('paid',15,2)->default('0');
            $table->date('payment_date')->nullable();
            $table->string('salary_status')->default('fail');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('staff_id')->references('id')->on('staffs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_salary');
    }
};
