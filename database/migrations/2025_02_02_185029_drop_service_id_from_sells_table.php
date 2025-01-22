<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sells', function (Blueprint $table) {
            if (Schema::hasColumn('sells', 'service_id')) {
                // First, drop foreign key if it exists
                $table->dropForeign(['service_id']);

                // Then, drop the column
                $table->dropColumn('service_id');
            }
        });
    }

    public function down()
    {
        Schema::table('sells', function (Blueprint $table) {
            // Add `service_id` back in case of rollback
            $table->unsignedBigInteger('service_id')->nullable();
        });
    }
};
