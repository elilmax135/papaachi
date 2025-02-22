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
        Schema::create('product', function (Blueprint $table) {
            $table->bigIncrements('product_id'); // Primary key


            $table->string('product_name');  // Name of the product (box or flower)
            $table->string('product_image')->nullable(); // Image of the product (box or flower)
            $table->string('product_type');  // Type (either 'box' or 'flower')
            $table->string('product_boxtype_id')->default('--');
            $table->string('color_id');
            $table->string('stock_quantity')->default('0');    // Color ID for box or flower
            $table->decimal('price_purchase', 15, 2);  // Purchase price
            $table->decimal('price_selling', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
