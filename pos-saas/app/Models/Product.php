<?php

namespace App\Models;

use App\Traits\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, BelongsToStore;

    protected $fillable = [
        'store_id',
        'category_id',
        'name',
        'sku',
        'description',
        'price',
        'cost_price',
        'stock',
        'low_stock_alert',
        'unit',
        'image',
        'barcode',
        'is_active',
    ];

    protected $casts = [
        'price'            => 'float',
        'cost_price'       => 'float',
        'stock'            => 'integer',
        'low_stock_alert'  => 'integer',
        'is_active'        => 'boolean',
    ];

    // ─── Relations ───────────────────────────────────────────────────────────

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('sku', 'LIKE', "%{$term}%")
              ->orWhere('barcode', 'LIKE', "%{$term}%");
        });
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeByCategory($query, ?int $categoryId)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function isLowStock(): bool
    {
        return $this->stock <= $this->low_stock_alert;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }

    public function decrementStock(int $qty): void
    {
        $this->decrement('stock', $qty);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/'.$this->image)
            : asset('images/no-image.png');
    }
}
