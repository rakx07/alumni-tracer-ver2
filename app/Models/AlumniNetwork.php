<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class AlumniNetwork extends Model
{
    protected $table = 'alumni_networks';

    protected $fillable = [
        'title',
        'link',
        'description',
        'logo_path',
        'is_active',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo_path) return null;

        // works with the "public" disk if you have `php artisan storage:link`
        return Storage::url($this->logo_path);
    }
}
