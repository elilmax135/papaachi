<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('transfer', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id2')->after('branch_id')->nullable(); // New branch reference
            $table->foreign('branch_id2')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::table('transfer', function (Blueprint $table) {
            $table->dropForeign(['branch_id2']);
            $table->dropColumn('branch_id2');
        });
    }
};
