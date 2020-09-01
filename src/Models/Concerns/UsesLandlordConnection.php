<?php declare(strict_types=1);

namespace Enlight\Multitenancy\Models\Concerns;

use Enlight\Multitenancy\Concerns\UsesMultitenancyConfig;

trait UsesLandlordConnection
{
    use UsesMultitenancyConfig;

    public function getConnectionName(): ?string
    {
        return $this->landlordDatabaseConnectionName();
    }
}
