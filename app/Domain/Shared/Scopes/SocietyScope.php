<?php

namespace App\Domain\Shared\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * SocietyScope
 *
 * Global scope that automatically filters all queries by the current society_id.
 * The current society ID is resolved from the application container binding 'current_society_id'.
 */
class SocietyScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (app()->bound('current_society_id')) {
            $societyId = app('current_society_id');

            if ($societyId) {
                $builder->where($model->getTable() . '.society_id', $societyId);
            }
        }
    }
}
