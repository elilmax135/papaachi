<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

  /**
     * Run the migrations.
     */
    return new class extends Migration {
        public function up() {
            Schema::create('stocks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('branch_id');  // Foreign Key
                $table->unsignedBigInteger('product_id'); // Foreign Key
                $table->string('branch_name');
                $table->string('product_name');
                $table->integer('total_quantity')->default(0);
                $table->timestamps();

                // Foreign Key Constraints
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
                $table->foreign('product_id')->references('product_id')->on('product')->onDelete('cascade');

            });
        }

        public function down() {
            Schema::dropIfExists('stocks');
        }
};
