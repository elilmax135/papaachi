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
        Schema::create('box_info', function (Blueprint $table) {
            $table->bigIncrements('box_unique_id')->startingValue(500); // Custom auto-incrementing primary key

             $table->string('box_name');           // Name of the box
            $table->string('box_image')->nullable();            // Image path or URL
            $table->string('bx_type_id');                 // Type/category of the box
            $table->string('bx_color_id');                   // Box color
            $table->decimal('price_purchase', 15, 2);   // Purchase price
            $table->decimal('price_selling', 15, 2);
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('box_info');
    }
};
