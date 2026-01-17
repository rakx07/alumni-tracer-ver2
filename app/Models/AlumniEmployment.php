<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniEmployment extends Model
{
    protected $fillable = [
        'alumnus_id',
        'current_status',
        'occupation_position',
        'company_name',
        'org_type',
        'work_address',
        'contact_info',
        'years_of_service_or_start',
        'licenses_certifications',
        'achievements_awards',
    ];

    public function alumnus()
    {
        return $this->belongsTo(Alumnus::class, 'alumnus_id');
    }
}
