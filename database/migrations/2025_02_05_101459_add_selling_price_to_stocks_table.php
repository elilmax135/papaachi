<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::table('stocks', function (Blueprint $table) {
            $table->decimal('selling_price', 15, 2)->after('total_quantity')->default(0.00); // Adding the selling price
        });
    }

    public function down() {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn('selling_price'); // Rollback if needed
        });
}
};
