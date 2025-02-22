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
        Schema::table('sell_product', function (Blueprint $table) {
            $table->dropColumn('purchase_price');
        });
    }

    public function down(): void
    {
        Schema::table('sell_product', function (Blueprint $table) {
            $table->decimal('purchase_price', 15, 2)->after('selling_price');
        });
    }
};
