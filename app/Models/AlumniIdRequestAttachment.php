<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniIdRequestAttachment extends Model
{
    protected $table = 'alumni_id_request_attachments';

    protected $fillable = [
        'request_id',
        'attachment_type',
        'file_path',
        'original_name',
        'uploaded_by',
    ];

    public function request()
    {
        return $this->belongsTo(AlumniIdRequest::class, 'request_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
