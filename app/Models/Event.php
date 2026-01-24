<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'is_published',
        'type',
        'organizer',
        'target_group',
        'audience',
        'registration_link',
        'contact_email',
        'poster_path',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_published' => 'boolean',
    ];
}
