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
        Schema::table('salary', function (Blueprint $table) {
            Schema::table('salary', function (Blueprint $table) {
                $table->integer('due')->default(0)->after('paid'); // Adding 'due' column after 'paid'
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary', function (Blueprint $table) {
            Schema::table('salary', function (Blueprint $table) {
                $table->dropColumn('due'); // Rollback if needed
            });
        });
    }
};
