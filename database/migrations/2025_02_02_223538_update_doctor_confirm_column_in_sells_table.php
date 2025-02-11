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
        Schema::table('sells', function (Blueprint $table) {
            $table->string('doctor_confirm')->nullable()->change(); // Modify the column to be nullable
        });
    }

    public function down(): void
    {
        Schema::table('sells', function (Blueprint $table) {
            $table->string('doctor_confirm')->nullable(false)->change(); // Revert to not nullable
        });
    }
};
