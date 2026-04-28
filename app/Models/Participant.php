<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    // This tells Laravel which columns we are allowed to fill with data
    protected $fillable = ['name', 'age', 'gender', 'date_joined'];

    // This defines the 1-to-Many relationship! (1 Participant has MANY Test Sessions)
    public function testSessions()
    {
        return $this->hasMany(TestSession::class);
    }
}