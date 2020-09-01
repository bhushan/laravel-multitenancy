<?php declare(strict_types=1);

namespace Enlight\Multitenancy\TenantFinder;

use Illuminate\Http\Request;
use Enlight\Multitenancy\Models\Tenant;

abstract class TenantFinder
{
    abstract public function findForRequest(Request $request): ?Tenant;
}
