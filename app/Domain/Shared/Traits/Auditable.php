<?php

namespace App\Domain\Shared\Traits;

use App\Domain\Audit\Models\ActivityLog;

/**
 * Auditable Trait
 *
 * Automatically logs create, update, and delete events to the activity_logs table.
 * Captures old/new values for change tracking and audit compliance.
 */
trait Auditable
{
    protected static function bootAuditable(): void
    {
        static::created(function ($model) {
            static::logActivity($model, 'created', [], $model->getAttributes());
        });

        static::updated(function ($model) {
            $oldValues = $model->getOriginal();
            $newValues = $model->getChanges();

            // Remove timestamps from diff
            unset($newValues['updated_at']);

            if (!empty($newValues)) {
                $filteredOld = array_intersect_key($oldValues, $newValues);
                static::logActivity($model, 'updated', $filteredOld, $newValues);
            }
        });

        static::deleted(function ($model) {
            static::logActivity($model, 'deleted', $model->getAttributes(), []);
        });
    }

    /**
     * Log an activity event.
     */
    protected static function logActivity($model, string $action, array $oldValues, array $newValues): void
    {
        try {
            ActivityLog::create([
                'society_id' => $model->society_id ?? (app()->bound('current_society_id') ? app('current_society_id') : null),
                'user_id' => auth()->id(),
                'action' => $action,
                'model_type' => get_class($model),
                'model_id' => $model->getKey(),
                'old_values' => !empty($oldValues) ? $oldValues : null,
                'new_values' => !empty($newValues) ? $newValues : null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Throwable $e) {
            // Silently fail — audit logging should never break the main flow
            report($e);
        }
    }

    /**
     * Get audit logs for this model instance.
     */
    public function activityLogs()
    {
        return ActivityLog::where('model_type', get_class($this))
            ->where('model_id', $this->getKey())
            ->orderByDesc('created_at');
    }
}
