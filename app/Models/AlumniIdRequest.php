<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniIdRequest extends Model
{
    protected $table = 'alumni_id_requests';

    protected $fillable = [
        'alumnus_id',
        'school_id',
        'last_name','first_name','middle_name',
        'course','grad_month','grad_year',
        'birthdate',
        'request_type',
        'signature_path',
        'status',
        'is_active_request',
        'remarks',
        'approved_at','processing_at','ready_at','released_at','declined_at',
        'last_acted_by',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'approved_at' => 'datetime',
        'processing_at' => 'datetime',
        'ready_at' => 'datetime',
        'released_at' => 'datetime',
        'declined_at' => 'datetime',
        'is_active_request' => 'integer',
    ];

    public function alumnus()
    {
        return $this->belongsTo(Alumnus::class, 'alumnus_id');
    }

    public function attachments()
    {
        return $this->hasMany(AlumniIdRequestAttachment::class, 'request_id');
    }

    public function logs()
    {
        return $this->hasMany(AlumniIdRequestLog::class, 'request_id')->orderBy('id');
    }

    public function lastActor()
    {
        return $this->belongsTo(User::class, 'last_acted_by');
    }

    // Helpers
    public function isActive(): bool
    {
        return (int)$this->is_active_request === 1;
    }
}
