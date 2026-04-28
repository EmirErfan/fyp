<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_session_id', 
        'type',
        'distress_item_01', 'distress_item_02', 'distress_item_03', 'distress_item_04', 'distress_item_05',
        'eustress_item_01', 'eustress_item_02', 'eustress_item_03', 'eustress_item_04'
    ];

    // This Assessment belongs to ONE specific Test Session
    public function testSession()
    {
        return $this->belongsTo(TestSession::class);
    }
}