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
        Schema::create('purchase', function (Blueprint $table) {
            $table->bigIncrements('purchase_id')->startingValue(12000); // Custom auto-incrementing primary key
            $table->text('purchase_product_id');
            $table->text('purchase_product_name');
            $table->text('quantity');
           $table->decimal('p_total_amount')->default('34');
           $table->text('purchase_date');
           $table->text('location_id');                   // Box color


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase');
    }
};
