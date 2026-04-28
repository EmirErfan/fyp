<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_session_id', 
        'accuracy_rate', 
        'average_reaction_time', 
        'total_error', 
        'face_video_path', 
        'screen_video_path'
    ];

    // This Result belongs to ONE specific Test Session
    public function testSession()
    {
        return $this->belongsTo(TestSession::class);
    }
}