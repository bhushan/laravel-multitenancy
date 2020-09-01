<?php declare(strict_types=1);

namespace Enlight\Multitenancy\TenantFinder;

use Illuminate\Http\Request;
use Enlight\Multitenancy\Models\Tenant;
use Enlight\Multitenancy\Models\Concerns\UsesTenantModel;

class DomainTenantFinder extends TenantFinder
{
    use UsesTenantModel;

    public function findForRequest(Request $request): ?Tenant
    {
        $host = $request->getHost();

        return $this->getTenantModel()::whereDomain($host)->first();
    }
}
