<?php

namespace App\Domain\Shared\Traits;

use App\Domain\Shared\Scopes\SocietyScope;

/**
 * HasSocietyScope Trait
 *
 * Automatically scopes all queries to the current society.
 * Apply this trait to any model that belongs to a society tenant.
 *
 * Usage:
 *   use HasSocietyScope;
 *
 * The model MUST have a `society_id` column.
 */
trait HasSocietyScope
{
    /**
     * Boot the trait: register global scope and auto-fill society_id on creation.
     */
    protected static function bootHasSocietyScope(): void
    {
        static::addGlobalScope(new SocietyScope());

        static::creating(function ($model) {
            if (empty($model->society_id) && app()->bound('current_society_id')) {
                $model->society_id = app('current_society_id');
            }
        });
    }

    /**
     * Relationship to society.
     */
    public function society()
    {
        return $this->belongsTo(\App\Domain\Society\Models\Society::class);
    }

    /**
     * Scope without the global society filter (for admin/cross-tenant queries).
     */
    public function scopeWithoutSocietyScope($query)
    {
        return $query->withoutGlobalScope(SocietyScope::class);
    }
}
