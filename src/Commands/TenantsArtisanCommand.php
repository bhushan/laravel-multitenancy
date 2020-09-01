<?php declare(strict_types=1);

namespace Enlight\Multitenancy\Commands;

use Illuminate\Console\Command;
use Enlight\Multitenancy\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Enlight\Multitenancy\Commands\Concerns\TenantAware;
use Enlight\Multitenancy\Concerns\UsesMultitenancyConfig;
use Enlight\Multitenancy\Models\Concerns\UsesTenantModel;

class TenantsArtisanCommand extends Command
{
    use UsesTenantModel, UsesMultitenancyConfig, TenantAware;

    protected $signature = 'tenants:artisan {artisanCommand} {--tenant=*}';

    public function handle(): void
    {
        if (! $artisanCommand = $this->argument('artisanCommand')) {
            $artisanCommand = $this->ask('Which artisan command do you want to run for all tenants?');
        }

        $tenant = Tenant::current();

        $this->line('');
        $this->info("Running command for tenant `{$tenant->name}` (id: {$tenant->getKey()})...");
        $this->line('---------------------------------------------------------');

        Artisan::call($artisanCommand, [], $this->output);
    }
}
