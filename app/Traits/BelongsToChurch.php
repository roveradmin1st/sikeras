<?php

namespace App\Traits;

use App\Scopes\ChurchScope;
use App\Services\TenantManager;

trait BelongsToChurch
{
    public static function bootBelongsToChurch()
    {
        static::addGlobalScope(new ChurchScope());

        static::creating(function ($model) {
            $tenantManager = app(TenantManager::class);
            if ($tenantManager->getTenantId() !== null) {
                $model->id_church = $tenantManager->getTenantId();
            }
        });
    }

    public function church()
    {
        return $this->belongsTo(\App\Models\Church::class, 'id_church', 'id_church');
    }
}
