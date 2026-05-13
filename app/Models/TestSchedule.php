<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'time', 'user_id'];

    public function researcher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ADD THIS FUNCTION: A schedule can have many assigned test sessions
    public function testSessions()
    {
        return $this->hasMany(TestSession::class);
    }
}