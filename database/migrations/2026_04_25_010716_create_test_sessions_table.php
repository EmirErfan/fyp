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
        Schema::create('test_sessions', function (Blueprint $table) {
            $table->id(); 
            
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); 
            $table->foreignId('participant_id')->constrained()->cascadeOnDelete(); 
            $table->foreignId('test_id')->constrained()->cascadeOnDelete(); 
            
            // WE CHANGED THIS LINE:
            $table->foreignId('test_schedule_id')->constrained()->cascadeOnDelete(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_sessions');
    }
};