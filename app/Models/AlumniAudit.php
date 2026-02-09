<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniAudit extends Model
{
    protected $table = 'alumni_audits';

    protected $fillable = [
        'alumnus_id','user_id','action','old_values','new_values'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function alumnus()
    {
        return $this->belongsTo(Alumnus::class, 'alumnus_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
