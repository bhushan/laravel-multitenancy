<?php declare(strict_types=1);

namespace Enlight\Multitenancy\Events;

use Enlight\Multitenancy\Models\Tenant;

class ForgettingCurrentTenantEvent
{
    public Tenant $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }
}
