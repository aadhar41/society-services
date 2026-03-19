<?php

namespace App\Domain\Audit\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ActivityLog — Full audit trail for all model changes.
 *
 * Stores old/new values as JSON for complete change history.
 * Used by the Auditable trait automatically.
 */
class ActivityLog extends Model
{
    protected $fillable = [
        'society_id', 'user_id', 'action', 'model_type', 'model_id',
        'old_values', 'new_values', 'ip_address', 'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user() { return $this->belongsTo(\App\Models\User::class); }

    public function scopeForModel($query, string $modelType, $modelId)
    {
        return $query->where('model_type', $modelType)->where('model_id', $modelId);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
