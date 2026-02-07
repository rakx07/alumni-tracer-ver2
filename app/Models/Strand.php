<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Strand extends Model
{
    protected $fillable = [
        'code',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /* =========================
     |  Scopes
     ========================= */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
