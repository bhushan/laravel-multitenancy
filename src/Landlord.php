<?php declare(strict_types=1);

namespace Enlight\Multitenancy;

use Enlight\Multitenancy\Models\Tenant;

class Landlord
{
    public static function execute(callable $callable)
    {
        $originalCurrentTenant = Tenant::current();

        Tenant::forgetCurrent();

        $result = $callable();

        optional($originalCurrentTenant)->makeCurrent();

        return $result;
    }
}
