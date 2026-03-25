<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'address',
        'phone',
        'logo',
        'currency',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Relations ───────────────────────────────────────────────────────────

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function cashierShifts(): HasMany
    {
        return $this->hasMany(CashierShift::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Generate slug from store name automatically.
     */
    public static function generateSlug(string $name): string
    {
        $slug = \Str::slug($name);
        $count = static::where('slug', 'LIKE', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }
}
