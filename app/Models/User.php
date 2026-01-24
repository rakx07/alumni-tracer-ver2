<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\NDMUVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $fillable = [
        'name',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'role',
        'is_active',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
        ];
    }

    /**
     * Full name (First Middle Last) – good for forms, letters, etc.
     */
    public function getFullNameAttribute(): string
    {
        return trim(
            ($this->first_name ?? '') . ' ' .
            (($this->middle_name ?? '') ? ($this->middle_name . ' ') : '') .
            ($this->last_name ?? '')
        );
    }

    /**
     * Role label for UI display.
     * Stored values: admin, alumni_officer, it_admin, user
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Admin',
            'alumni_officer' => 'Alumni Officer',
            'it_admin' => 'IT Admin',
            default => 'User',
        };
    }

    /**
     * Vertical name display:
     * LASTNAME
     * FIRSTNAME
     * MIDDLENAME (optional)
     */
    public function getVerticalNameAttribute(): string
    {
        $last   = strtoupper((string) ($this->last_name ?? ''));
        $first  = strtoupper((string) ($this->first_name ?? ''));
        $middle = strtoupper((string) ($this->middle_name ?? ''));

        return trim($last) . "\n" . trim($first) . ($middle ? "\n" . trim($middle) : '');
    }

    /**
     * Optional: Keep "name" consistent (used in your dashboard: $user->name)
     * Call $user->syncDisplayName() after create/update if you want.
     */
    public function syncDisplayName(): void
    {
        $this->name = $this->full_name;
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new NDMUVerifyEmail);
    }
}
