<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Participant;
use App\Models\TestSchedule;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Call your custom seeders first!
        $this->call([
            AdminSeeder::class,
            TestSeeder::class,
        ]);

        // 2. Create the Researcher (Since AdminSeeder only makes the Admin)
        User::create([
            'name' => 'Dr. Researcher',
            'email' => 'researcher@system.com',
            'password' => Hash::make('password123'),
            'role' => 'researcher',
        ]);

        // 3. Create Participants (Using your exact data)
        Participant::create(['name' => 'Ahmad Emir Erfan', 'dob' => '2002-07-02', 'gender' => 'Male', 'date_joined' => now()]);
        Participant::create(['name' => 'Ilia', 'dob' => '2005-05-08', 'gender' => 'Female', 'date_joined' => now()]);
        Participant::create(['name' => 'Harry Maguire', 'dob' => '2003-01-10', 'gender' => 'Male', 'date_joined' => now()]);

        // 4. Create some Schedules
        TestSchedule::create(['date' => now()->addDays(1)->toDateString(), 'time' => '10:00:00']);
        TestSchedule::create(['date' => now()->addDays(1)->toDateString(), 'time' => '14:30:00']);
        TestSchedule::create(['date' => now()->addDays(2)->toDateString(), 'time' => '09:15:00']);
    }
}