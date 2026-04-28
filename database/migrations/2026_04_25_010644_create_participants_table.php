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
        Schema::create('participants', function (Blueprint $table) {
            $table->id(); // This automatically creates an auto-incrementing Primary Key
            $table->string('name'); // A standard text column for the participant's name
            $table->integer('age'); // A number column for their age
            $table->string('gender'); // A text column for gender (e.g., Male, Female)
            $table->date('date_joined'); // A specific date column for when they registered
            $table->timestamps(); // This automatically creates 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};