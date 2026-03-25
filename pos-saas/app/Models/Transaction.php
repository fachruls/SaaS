<?php

namespace App\Models;

use App\Traits\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory, BelongsToStore;

    protected $fillable = [
        'store_id',
        'cashier_shift_id',
        'user_id',
        'invoice_number',
        'subtotal',
        'discount_amount',
        'discount_percent',
        'tax_percent',
        'tax_amount',
        'total',
        'payment_method',
        'amount_paid',
        'change_amount',
        'payment_reference',
        'notes',
        'status',
    ];

    protected $casts = [
        'subtotal'         => 'float',
        'discount_amount'  => 'float',
        'discount_percent' => 'float',
        'tax_percent'      => 'float',
        'tax_amount'       => 'float',
        'total'            => 'float',
        'amount_paid'      => 'float',
        'change_amount'    => 'float',
    ];

    // ─── Relations ───────────────────────────────────────────────────────────

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function cashierShift(): BelongsTo
    {
        return $this->belongsTo(CashierShift::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public static function generateInvoiceNumber(int $storeId): string
    {
        $prefix = 'INV';
        $date   = now()->format('Ymd');
        $last   = static::withoutGlobalScopes()
            ->where('store_id', $storeId)
            ->whereDate('created_at', today())
            ->count();
        return sprintf('%s-%s-%s-%04d', $prefix, $storeId, $date, $last + 1);
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'cash'     => '💵 Tunai',
            'qris'     => '📱 QRIS',
            'transfer' => '🏦 Transfer',
            'card'     => '💳 Kartu',
            default    => ucfirst($this->payment_method),
        };
    }
}
