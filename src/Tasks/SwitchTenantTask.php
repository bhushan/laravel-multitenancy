<?php declare(strict_types=1);

namespace Enlight\Multitenancy\Tasks;

use Enlight\Multitenancy\Models\Tenant;

interface SwitchTenantTask
{
    public function makeCurrent(Tenant $tenant): void;

    public function forgetCurrent(): void;
}
