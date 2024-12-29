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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('email');
            $table->string('mobile');
            $table->string('favicon');
            $table->string('logo');
            $table->string('login_img');
            $table->string('profile');
            $table->text('desc');
            $table->text('tags');
            $table->string('solution');
            $table->string('main_color')->default('#BD1701');
            $table->string('second_color')->default('#ED523D');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
