<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Participant extends Model
{
    // FIXED: Changed 'researcher_id' to 'user_id' so it matches the database
    protected $fillable = [
        'name', 'dob', 'gender', 'email', 'phone', 
        'date_joined', 'occupation', 'education_level', 
        'color_blindness', 'existing_condition', 'user_id', 'team_id'
    ];

    // Define relationship to the Researcher (User)
    public function researcher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Accessor to magically calculate the age
    public function getAgeAttribute()
    {
        return Carbon::parse($this->attributes['dob'])->age;
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}