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
        Schema::table('box_info', function (Blueprint $table) {
            $table->string('box_image')->nullable()->change(); // Make the column nullable
        });
    }

    public function down(): void
    {
        Schema::table('box_info', function (Blueprint $table) {
            $table->string('box_image')->nullable(false)->change(); // Revert the column to NOT NULL
        });
    }
};
