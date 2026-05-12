<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Test; 

class TestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. The Stroop Test
        Test::create([
            'test_type' => 'Stroop Test',
            'test_level' => 'Standard'
        ]);

        // 2. The MIST (Combined into a single test)
        Test::create([
            'test_type' => 'MIST',
            'test_level' => 'Cognitive Load'
        ]);

        // 3. The TSST Arithmetic Task
        Test::create([
            'test_type' => 'TSST',
            'test_level' => 'Arithmetic Task'
        ]);
    }
}