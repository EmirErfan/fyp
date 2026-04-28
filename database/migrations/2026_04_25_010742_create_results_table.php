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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            
            // Link this result back to the session
            $table->foreignId('test_session_id')->constrained()->cascadeOnDelete();
            
            // Test metrics
            $table->decimal('accuracy_rate', 5, 2); // Allows numbers like 95.50
            $table->decimal('average_reaction_time', 8, 2); // e.g., 1450.25 (milliseconds)
            $table->integer('total_error'); // Whole number for amount of mistakes
            
            // Video Paths
            // We use nullable() because the video might take a few seconds to upload AFTER the test finishes!
            $table->string('face_video_path')->nullable(); 
            $table->string('screen_video_path')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};