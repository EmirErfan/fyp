<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'time'];

    // ADD THIS FUNCTION: A schedule can have many assigned test sessions
    public function testSessions()
    {
        return $this->hasMany(TestSession::class);
    }
}