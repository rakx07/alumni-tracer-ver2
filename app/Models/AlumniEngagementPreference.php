<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniEngagementPreference extends Model
{
    protected $fillable = [
        'alumnus_id',
        'willing_contacted',
        'willing_events',
        'willing_mentor',
        'willing_donation',
        'willing_scholarship',
        'prefer_email',
        'prefer_sms',
        'prefer_facebook',
    ];

    public function alumnus()
    {
        return $this->belongsTo(Alumnus::class, 'alumnus_id');
    }
}
