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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            
            // Link this assessment to the specific test session
            $table->foreignId('test_session_id')->constrained()->cascadeOnDelete();
            
            // This column will store either 'pre' or 'post' so we know which one it is!
            $table->enum('type', ['pre', 'post']); 
            
            // The 5 Distress Items (storing integers, e.g., a scale of 1-5)
            $table->integer('distress_item_01');
            $table->integer('distress_item_02');
            $table->integer('distress_item_03');
            $table->integer('distress_item_04');
            $table->integer('distress_item_05');
            
            // The 4 Eustress Items
            $table->integer('eustress_item_01');
            $table->integer('eustress_item_02');
            $table->integer('eustress_item_03');
            $table->integer('eustress_item_04');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};