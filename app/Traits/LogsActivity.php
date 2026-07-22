<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        // Al crear
        static::created(function ($model) {
           $model->recordActivity('created', $model->getAttributes());
        });

        // Al actualizar
        static::updated(function ($model) {
            $changes = $model->getChanges();
            unset($changes['updated_at']); // no nos interesa el timestamp

            if (!empty($changes)) {
                $model->recordActivity('updated', $changes);
            }
        });

        // Al eliminar
        static::deleted(function ($model) {
            $model->recordActivity('deleted', $model->getAttributes());
        });
    }

    public function recordActivity(string $action, array $changes = []): void
    {
        ActivityLog::create([
            'user_id'        => auth()->id(), // null si no hay sesión (seeders, etc.)
            'action'         => $action,
            'auditable_type' => static::class,
            'auditable_id'   => $this->getKey(),
            'changes'        => $changes,
        ]);
    }
}
