<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniCommunityInvolvement extends Model
{
    protected $fillable = ['alumnus_id','organization','role_position','years_involved'];

    public function alumnus()
    {
        return $this->belongsTo(Alumnus::class, 'alumnus_id');
    }
}
