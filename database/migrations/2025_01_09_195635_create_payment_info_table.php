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
        Schema::create('payment_info', function (Blueprint $table) {
            $table->id('payment_id');
            $table->string('purchase_id');
            $table->string('payment_method');
            $table->string('check_number')->default('--');
            $table->string('bank_name')->default('--');
            $table->string('transection_id')->default('--');
            $table->string('payment_platform')->default('--');
            $table->date('payment_date');
            $table->decimal('purchase_total', 15, 2);
            $table->decimal('pay_amount', 15, 2);
            $table->decimal('pay_due', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_info');
    }
};
