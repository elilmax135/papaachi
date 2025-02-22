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
        Schema::create('sells', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_mobile');
            $table->date('sell_date');
            $table->string('transaction_id');
            $table->string('place');
            $table->text('customer_address');
            $table->string('doctor_confirm')->nullable();
            $table->unsignedBigInteger('branch_id');
            $table->decimal('total', 15, 2);
            $table->string('sell_status')->default('fail');
            $table->string('panthal_amount')->default('0');
            $table->string('empoming_amount')->default('0');
            $table->string('emapoming_days')->default('0');
            $table->string('empoming_type')->nullable();
            $table->string('lift_amount')->default('0');
            $table->string('band_amount')->default('0');
            $table->string('melam_amount')->default('0');
            $table->string('transport_amount')->default('0');
            $table->string('flower_ring')->default('0');
            $table->string('ac_room')->default('0');
            $table->string('ac_room_days')->default('0');
            $table->timestamps();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sells');
    }
};
