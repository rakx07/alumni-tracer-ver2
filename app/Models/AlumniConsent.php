<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniConsent extends Model
{
    protected $fillable = ['alumnus_id','signature_name','consented_at','ip_address'];

    public function alumnus()
    {
        return $this->belongsTo(Alumnus::class, 'alumnus_id');
    }
}
