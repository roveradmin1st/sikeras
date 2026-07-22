<?php

namespace App\Services;

use App\Models\Church;

class TenantManager
{
    protected ?Church $church = null;

    public function setTenant(?Church $church): void
    {
        $this->church = $church;
    }

    public function getTenant(): ?Church
    {
        return $this->church;
    }

    public function getTenantId(): ?int
    {
        return $this->church ? $this->church->id_church : null;
    }
}
