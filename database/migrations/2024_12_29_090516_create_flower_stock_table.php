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
        Schema::create('flower_stock', function (Blueprint $table) {
            $table->id();
            $table->string('stock_name');
             $table->decimal('price', 10, 2)->nullable();
             $table->integer('quantity')->nullable();
            $table->string('flower_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flower_stock');
    }
};
