<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // Make sure this is here!

class Participant extends Model
{
    // 1. Update fillable to use dob instead of age
    protected $fillable = ['name', 'dob', 'gender', 'date_joined'];

    // 2. Add this "Accessor" to magically calculate the age for your table!
    public function getAgeAttribute()
    {
        return Carbon::parse($this->attributes['dob'])->age;
    }
}