<?php declare(strict_types=1);

namespace Enlight\Multitenancy\Tasks;

use Enlight\Multitenancy\Models\Tenant;

class PrefixCacheTask implements SwitchTenantTask
{
    protected ?string $originalPrefix;

    private string $storeName;

    private string $cacheKeyBase;

    public function __construct(string $storeName = null, string $cacheKeyBase = null)
    {
        $this->originalPrefix = config('cache.prefix');

        $this->storeName = $storeName ?? config('cache.default');

        $this->cacheKeyBase = $cacheKeyBase ?? 'tenant_id_';
    }

    public function makeCurrent(Tenant $tenant): void
    {
        $this->setCachePrefix($this->cacheKeyBase . $tenant->id);
    }

    public function forgetCurrent(): void
    {
        $this->setCachePrefix($this->originalPrefix);
    }

    protected function setCachePrefix(string $prefix): void
    {
        config()->set('cache.prefix', $prefix);

        app('cache')->forgetDriver($this->storeName);
    }
}
