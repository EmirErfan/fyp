<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessRequest extends Model
{
    // Explicitly define the table name to prevent any routing/migration mismatches
    protected $table = 'access_requests';

    // Force the default status to 'pending' before it even hits the database
    protected $attributes = [
        'status' => 'pending',
    ];

    protected $fillable = [
        'name',
        'email',
        'institution',
        'purpose',
        'status',
    ];
}