<?php

namespace App\Traits;

use App\Models\Tenant;

trait TenantTrait
{
    protected $tenant;

    /**
     * Return the current tenant based on the connected user 
     */
    public function tenant(): ?Tenant
    {
        if ($this->tenant) {
            return $this->tenant;
        }

        $user = auth()->user();
        if ($user && $user->tenant_id) {
            $this->tenant = Tenant::find($user->tenant_id);
            return $this->tenant;
        }

        return null;
    }
}