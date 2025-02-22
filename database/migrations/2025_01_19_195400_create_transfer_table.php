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
        Schema::create('transfer', function (Blueprint $table) {
            $table->id();
            $table->date('transfer_date');
            $table->string('transaction_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('branch_id2')->nullable();
            $table->decimal('total', 15, 2);
            $table->string('transfer_status')->default('fail');

            $table->timestamps();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
           // New branch reference
            $table->foreign('branch_id2')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer');
    }
};
