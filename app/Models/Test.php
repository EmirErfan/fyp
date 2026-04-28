<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = ['test_type', 'test_level'];

    // 1 Test type can belong to MANY Test Sessions
    public function testSessions()
    {
        return $this->hasMany(TestSession::class);
    }
}