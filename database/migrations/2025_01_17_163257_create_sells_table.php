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
            $table->string('transport_mode');
            $table->text('customer_address');
            $table->string('doctor_confirm');
            $table->unsignedBigInteger('service_id');
            $table->decimal('total', 10, 2);
            $table->string('sell_status')->default('fail');
            $table->timestamps();
            $table->foreign('service_id')->references('service_id_uniq')->on('service')->onDelete('cascade');

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
