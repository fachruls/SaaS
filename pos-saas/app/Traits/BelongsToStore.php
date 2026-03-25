<?php

namespace App\Traits;

use App\Scopes\StoreScope;

trait BelongsToStore
{
    /**
     * Boot the trait — apply global scope and auto-fill store_id on creation.
     */
    public static function bootBelongsToStore(): void
    {
        // Apply global scope so queries are always filtered by store
        static::addGlobalScope(new StoreScope());

        // Automatically inject store_id when creating a new record
        static::creating(function ($model) {
            if (auth()->check() && ! auth()->user()->isSuperAdmin() && empty($model->store_id)) {
                $model->store_id = auth()->user()->store_id;
            }
        });
    }

    /**
     * Query without the store scope (useful for Super Admin reports).
     */
    public static function allStores()
    {
        return static::withoutGlobalScope(StoreScope::class);
    }
}
