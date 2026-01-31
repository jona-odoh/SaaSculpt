<?php

namespace App\Services;

use App\Models\Tenant;

class CurrentTenant
{
    protected ?Tenant $tenant = null;

    public function set(Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }

    public function get(): ?Tenant
    {
        return $this->tenant;
    }

    public function getId(): ?int
    {
        return $this->tenant?->id;
    }
}
