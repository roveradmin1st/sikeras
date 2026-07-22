<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use App\Services\TenantManager;

class ChurchScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $tenantManager = app(TenantManager::class);
        $tenantId = $tenantManager->getTenantId();
        
        if ($tenantId !== null) {
            $builder->where($model->getTable() . '.id_church', $tenantId);
        }
    }
}
