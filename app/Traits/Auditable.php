<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

trait Auditable
{
    /**
     * Boot the trait to listen for model events.
     */
    public static function bootAuditable(): void
    {
        static::created(function (Model $model) {
            static::logEvent($model, 'created', null, $model->getAuditAttributes());
        });

        static::updated(function (Model $model) {
            $old = [];
            $new = [];
            foreach ($model->getChanges() as $key => $value) {
                if (in_array($key, ['updated_at', 'password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])) {
                    continue;
                }
                $old[$key] = $model->getOriginal($key);
                $new[$key] = $value;
            }
            if (! empty($new)) {
                static::logEvent($model, 'updated', $old, $new);
            }
        });

        static::deleted(function (Model $model) {
            static::logEvent($model, 'deleted', $model->getAuditAttributes(), null);
        });
    }

    /**
     * Filter sensitive attributes before logging.
     */
    protected function getAuditAttributes(): array
    {
        $attributes = $this->getAttributes();
        $sensitive = ['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'];
        foreach ($sensitive as $field) {
            unset($attributes[$field]);
        }

        return $attributes;
    }

    /**
     * Log the event to the database.
     */
    protected static function logEvent(Model $model, string $event, ?array $old, ?array $new): void
    {
        $userId = auth()->id();
        $user = auth()->user();
        $modelName = class_basename($model);
        $modelId = $model->getKey();

        // Except for client actions, only log order confirmation
        if ($user && $user->role === 'cliente') {
            if ($modelName !== 'Order' || $event !== 'created') {
                return;
            }
        }

        $translatedModels = [
            'Product' => 'Producto',
            'Category' => 'Categoría',
            'Order' => 'Pedido',
            'Promocion' => 'Promoción',
            'User' => 'Usuario',
            'AuditReport' => 'Informe de Auditoría',
        ];
        $displayName = $translatedModels[$modelName] ?? $modelName;

        if ($modelName === 'Order' && $event === 'created') {
            $userName = $user?->name ?? 'Cliente';
            $description = "Pedido {$model->numero_pedido} confirmado por {$userName} (ID: {$modelId}) y listo para revisión.";
        } else {
            $description = match ($event) {
                'created' => "Se creó el registro en {$displayName} (ID: {$modelId})",
                'updated' => "Se actualizó el registro en {$displayName} (ID: {$modelId})",
                'deleted' => "Se eliminó el registro en {$displayName} (ID: {$modelId})",
                default => "Evento {$event} en {$displayName} (ID: {$modelId})",
            };
        }

        AuditLog::create([
            'user_id' => $userId,
            'event' => $event,
            'model_type' => get_class($model),
            'model_id' => $modelId,
            'description' => $description,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
