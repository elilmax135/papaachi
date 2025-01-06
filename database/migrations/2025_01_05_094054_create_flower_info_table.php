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
        Schema::create('flower_info', function (Blueprint $table) {

            $table->bigIncrements('flower_unique_id')->startingValue(7000); // Primary key for flower_info

            // Flower-specific fields
            $table->string('flower_name');
            $table->string('flower_image');
            $table->string('fw_color_id');
            $table->decimal('price_purchase', 10, 2);
            $table->decimal('price_selling', 10, 2);

            $table->timestamps();
        });

        // Setting up the foreign key relationship outside the create closure

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flower_info');
    }
};
