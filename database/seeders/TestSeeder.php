<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Test; // Import our Test model!

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. The Stroop Test
        Test::create([
            'test_type' => 'Stroop Test',
            'test_level' => 'Standard'
        ]);

        // 2. The MIST - Control Phase
        Test::create([
            'test_type' => 'MIST',
            'test_level' => 'Control Phase'
        ]);

        // 3. The MIST - Experimental Phase
        Test::create([
            'test_type' => 'MIST',
            'test_level' => 'Experimental Phase'
        ]);

        // 4. The TSST Arithmetic Task
        Test::create([
            'test_type' => 'TSST',
            'test_level' => 'Arithmetic Task'
        ]);
    }
}