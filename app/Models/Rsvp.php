<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rsvp extends Model
{
    protected $fillable = [
        'name',
        'email',
        'attending',
        'additional_guests',
        'message',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}