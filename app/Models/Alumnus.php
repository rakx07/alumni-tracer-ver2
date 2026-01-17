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
}
