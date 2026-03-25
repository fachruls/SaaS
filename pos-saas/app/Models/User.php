<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'store_id',
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ─── Role Helpers ────────────────────────────────────────────────────────

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCashier(): bool
    {
        return $this->role === 'cashier';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function canAccessPos(): bool
    {
        return in_array($this->role, ['admin', 'cashier']);
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function cashierShifts(): HasMany
    {
        return $this->hasMany(CashierShift::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeOfStore($query, int $storeId)
    {
        return $query->where('store_id', $storeId);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
