<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class StoreScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     * Super Admin bypass: can see all stores' data.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (auth()->check() && ! auth()->user()->isSuperAdmin()) {
            $builder->where($model->getTable().'.store_id', auth()->user()->store_id);
        }
    }
}
