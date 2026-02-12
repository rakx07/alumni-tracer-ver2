<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CareerPost extends Model
{
    protected $table = 'career_posts';

    protected $fillable = [
        'title',
        'company',
        'location',
        'employment_type',
        'summary',
        'description',
        'how_to_apply',
        'apply_url',
        'apply_email',
        'start_date',
        'end_date',
        'is_published',
        'created_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'start_date'   => 'date',
        'end_date'     => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(CareerPostAttachment::class, 'career_post_id')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    // ---- Status helpers (used later for labels: Upcoming / Active / Expired) ----

    public function isUpcoming(): bool
    {
        if (!$this->is_published) return false;
        if (!$this->start_date) return false;
        return now()->startOfDay()->lt($this->start_date->startOfDay());
    }

    public function isExpired(): bool
    {
        if (!$this->end_date) return false;
        return now()->startOfDay()->gt($this->end_date->startOfDay());
    }

    public function isActiveToday(): bool
    {
        if (!$this->is_published) return false;

        $today = now()->startOfDay();

        if ($this->start_date && $today->lt($this->start_date->startOfDay())) return false;
        if ($this->end_date && $today->gt($this->end_date->startOfDay())) return false;

        return true;
    }

    public function statusLabel(): string
    {
        if (!$this->is_published) return 'Hidden';
        if ($this->isUpcoming()) return 'Upcoming';
        if ($this->isExpired()) return 'Expired';
        return 'Active';
    }
}
