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
            $table->id();
            $table->string('flower_name')->unique();
            $table->string('flower_image')->nullable();
            $table->string('color')->nullable();
             $table->decimal('price', 10, 2)->nullable();
             $table->integer('quantity')->nullable();
             $table->string('branch_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flower_info');
    }
};
