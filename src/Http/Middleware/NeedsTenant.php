<?php declare(strict_types=1);

namespace Enlight\Multitenancy\Http\Middleware;

use Closure;
use Enlight\Multitenancy\Exceptions\NoCurrentTenant;
use Enlight\Multitenancy\Models\Concerns\UsesTenantModel;

class NeedsTenant
{
    use UsesTenantModel;

    public function handle($request, Closure $next)
    {
        if (! $this->getTenantModel()::checkCurrent()) {
            return $this->handleInvalidRequest();
        }

        return $next($request);
    }

    public function handleInvalidRequest(): void
    {
        throw NoCurrentTenant::make();
    }
}
