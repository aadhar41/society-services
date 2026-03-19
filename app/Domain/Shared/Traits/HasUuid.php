<?php

namespace App\Domain\Shared\Traits;

use Illuminate\Support\Str;

/**
 * HasUuid Trait
 *
 * Automatically generates a UUID for the model on creation.
 * The model MUST have a `uuid` column.
 */
trait HasUuid
{
    protected static function bootHasUuid(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the route key name for implicit route binding.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Scope to find by UUID.
     */
    public function scopeByUuid($query, string $uuid)
    {
        return $query->where('uuid', $uuid);
    }
}
