<?php declare(strict_types=1);

namespace Enlight\Multitenancy\Actions;

use Enlight\Multitenancy\Models\Tenant;
use Enlight\Multitenancy\Tasks\TasksCollection;
use Enlight\Multitenancy\Tasks\SwitchTenantTask;
use Enlight\Multitenancy\Events\MadeTenantCurrentEvent;
use Enlight\Multitenancy\Events\MakingTenantCurrentEvent;

class MakeTenantCurrentAction
{
    protected TasksCollection $tasksCollection;

    public function __construct(TasksCollection $tasksCollection)
    {
        $this->tasksCollection = $tasksCollection;
    }

    public function execute(Tenant $tenant): self
    {
        event(new MakingTenantCurrentEvent($tenant));

        $this
            ->performTasksToMakeTenantCurrent($tenant)
            ->bindAsCurrentTenant($tenant);

        event(new MadeTenantCurrentEvent($tenant));

        return $this;
    }

    protected function performTasksToMakeTenantCurrent(Tenant $tenant): self
    {
        $this->tasksCollection->each(fn (SwitchTenantTask $task) => $task->makeCurrent($tenant));

        return $this;
    }

    protected function bindAsCurrentTenant(Tenant $tenant): self
    {
        $containerKey = config('multitenancy.current_tenant_container_key');

        app()->forgetInstance($containerKey);

        app()->instance($containerKey, $tenant);

        return $this;
    }
}
