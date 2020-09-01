<?php declare(strict_types=1);

namespace Enlight\Multitenancy\Models\Concerns;

use Enlight\Multitenancy\Models\Tenant;

trait UsesTenantModel
{
    public function getTenantModel(): Tenant
    {
        $tenantModelClass = config('multitenancy.tenant_model');

        return new $tenantModelClass;
    }
}
