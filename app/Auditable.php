<?php

namespace App;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function (Model $model) {
            $model->createAuditLog('created');
        });

        static::updated(function (Model $model) {
            $model->createAuditLog('updated');
        });

        static::deleted(function (Model $model) {
            $model->createAuditLog('deleted');
        });
    }

    protected function createAuditLog(string $action): void
    {
        $oldValues = null;
        $newValues = null;

        if ($action === 'created') {
            $newValues = $this->getAttributes();
        }

        if ($action === 'updated') {
            $oldValues = array_intersect_key(
                $this->getOriginal(),
                $this->getChanges()
            );

            $newValues = $this->getChanges();
        }

        if ($action === 'deleted') {
            $oldValues = $this->getOriginal();
        }

        // Never store sensitive information such as passwords.
        unset(
            $oldValues['password'],
            $oldValues['remember_token'],
            $newValues['password'],
            $newValues['remember_token']
        );

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'module' => class_basename($this),
            'auditable_type' => get_class($this),
            'auditable_id' => $this->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => app()->runningInConsole()
                ? null
                : request()->ip(),
            'user_agent' => app()->runningInConsole()
                ? null
                : request()->userAgent(),
        ]);
    }
}