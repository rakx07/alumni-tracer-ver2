<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alumnus extends Model
{
    use SoftDeletes;

    protected $table = 'alumni';

    protected $fillable = [
    'user_id',
    'full_name',

    // ✅ Maiden name fields
    'maiden_first_name',
    'maiden_middle_name',
    'maiden_last_name',

    'nickname',
    'sex',
    'birthdate',
    'age',
    'civil_status',
    'home_address',
    'current_address',
    'contact_number',
    'email',
    'facebook',
    'nationality',
    'religion',
    'encoded_by',
    'encoding_mode',
    'record_status',
    'validated_by',
    'validated_at',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function educations()
    {
        return $this->hasMany(AlumniEducation::class, 'alumnus_id');
    }

    public function employments()
    {
        return $this->hasMany(AlumniEmployment::class, 'alumnus_id');
    }

    public function communityInvolvements()
    {
        return $this->hasMany(AlumniCommunityInvolvement::class, 'alumnus_id');
    }

    public function engagementPreference()
    {
        return $this->hasOne(AlumniEngagementPreference::class, 'alumnus_id');
    }

    public function consent()
    {
        return $this->hasOne(AlumniConsent::class, 'alumnus_id');
    }
    protected $casts = [
    'birthdate' => 'date',
    'validated_at' => 'datetime',
    ];
public function audits()
{
    return $this->hasMany(AlumniAudit::class, 'alumnus_id');
}

//For ID Request link
public function alumniIdRequests()
{
    return $this->hasMany(\App\Models\AlumniIdRequest::class, 'alumnus_id');
}

public function activeAlumniIdRequest()
{
    return $this->hasOne(\App\Models\AlumniIdRequest::class, 'alumnus_id')
        ->where('is_active_request', 1);
}


}
