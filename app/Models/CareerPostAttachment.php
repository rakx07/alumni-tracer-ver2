<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerPostAttachment extends Model
{
    protected $table = 'career_post_attachments';

    protected $fillable = [
        'career_post_id',
        'path',
        'original_name',
        'mime_type',
        'size',
        'sort_order',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(CareerPost::class, 'career_post_id');
    }
}
