<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSession extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'participant_id', 'test_id', 'test_schedule_id'];

    // --- "Belongs To" Relationships (Going up to the parents) ---
    
    public function user()
    {
        return $this->belongsTo(User::class); // The researcher
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class); // The test subject
    }

    public function test()
    {
        return $this->belongsTo(Test::class); // The specific test (Stroop, MIST, etc.)
    }

    public function testSchedule()
    {
        return $this->belongsTo(TestSchedule::class); // The date and time slot
    }

    // --- "Has" Relationships (Going down to the children) ---

    // 1 Session has MANY Assessments (Exactly two: one 'pre' and one 'post')
    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    // 1 Session has exactly ONE final Result
    public function result()
    {
        return $this->hasOne(Result::class);
    }
}