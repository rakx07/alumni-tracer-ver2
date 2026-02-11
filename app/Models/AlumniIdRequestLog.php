<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniIdRequestLog extends Model
{
    protected $table = 'alumni_id_request_logs';

    protected $fillable = [
        'request_id',
        'actor_user_id',
        'action',
        'remarks',
    ];

    public function request()
    {
        return $this->belongsTo(AlumniIdRequest::class, 'request_id');
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }
}
