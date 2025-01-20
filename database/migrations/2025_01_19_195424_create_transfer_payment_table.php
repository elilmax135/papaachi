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
        Schema::create('transfer_payment', function (Blueprint $table) {
            $table->id('transfer_pay_id');
            $table->unsignedBigInteger('transfer_id');
            $table->string('payment_method');
            $table->string('check_number')->default('--');
            $table->string('bank_name')->default('--');
            $table->string('transection_id')->default('--');
            $table->string('payment_platform')->default('--');
            $table->date('payment_date');
            $table->decimal('transfer_total', 10, 2);
            $table->decimal('pay_amount', 10, 2);
            $table->decimal('pay_due', 10, 2)->default(0);
            $table->timestamps();
            $table->foreign('transfer_id')->references('id')->on('transfer')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_payment');
    }
};
