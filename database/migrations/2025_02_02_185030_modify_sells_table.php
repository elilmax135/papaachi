<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('sells', function (Blueprint $table) {
            // Drop the old column
            $table->dropColumn('service_id');

            // Add the new column
            $table->unsignedBigInteger('branch_id')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('sells', function (Blueprint $table) {
            // Reverse changes: Add service_id back
            $table->unsignedBigInteger('service_id')->nullable()->after('id');

            // Drop the branch_id column
            $table->dropColumn('branch_id');
        });
    }
};
