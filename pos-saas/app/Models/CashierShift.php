<?php

namespace App\Models;

use App\Traits\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashierShift extends Model
{
    use HasFactory, BelongsToStore;

    protected $fillable = [
        'store_id',
        'user_id',
        'opening_balance',
        'closing_balance',
        'total_sales',
        'total_transactions',
        'opening_notes',
        'closing_notes',
        'status',
        'opened_at',
        'closed_at',
    ];

    protected $casts = [
        'opening_balance'    => 'float',
        'closing_balance'    => 'float',
        'total_sales'        => 'float',
        'total_transactions' => 'integer',
        'opened_at'          => 'datetime',
        'closed_at'          => 'datetime',
    ];

    // ─── Relations ───────────────────────────────────────────────────────────

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function getDurationAttribute(): string
    {
        $end = $this->closed_at ?? now();
        return $this->opened_at->diffForHumans($end, true);
    }
}
