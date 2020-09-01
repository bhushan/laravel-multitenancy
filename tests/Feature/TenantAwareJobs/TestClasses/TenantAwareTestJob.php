<?php declare(strict_types=1);

namespace Enlight\Multitenancy\Tests\Feature\TenantAwareJobs\TestClasses;

use Enlight\Multitenancy\Jobs\TenantAware;

class TenantAwareTestJob extends TestJob implements TenantAware
{
}
