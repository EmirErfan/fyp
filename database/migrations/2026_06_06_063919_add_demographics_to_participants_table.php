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
        Schema::table('participants', function (Blueprint $table) {
            $table->string('contact_info')->nullable();
            $table->string('occupation')->nullable();
            $table->string('education_level')->nullable();
            $table->string('color_blindness')->default('None');
            $table->string('existing_condition')->default('Normal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            //
        });
    }
};
