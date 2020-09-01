<?php declare(strict_types=1);

namespace Enlight\Multitenancy\Actions;

use Enlight\Multitenancy\Models\Tenant;
use Enlight\Multitenancy\Tasks\TasksCollection;
use Enlight\Multitenancy\Tasks\SwitchTenantTask;
use Enlight\Multitenancy\Events\ForgotCurrentTenantEvent;
use Enlight\Multitenancy\Events\ForgettingCurrentTenantEvent;

class ForgetCurrentTenantAction
{
    private TasksCollection $tasksCollection;

    public function __construct(TasksCollection $tasksCollection)
    {
        $this->tasksCollection = $tasksCollection;
    }

    public function execute(Tenant $tenant): void
    {
        event(new ForgettingCurrentTenantEvent($tenant));

        $this
            ->performTaskToForgetCurrentTenant()
            ->clearBoundCurrentTenant();

        event(new ForgotCurrentTenantEvent($tenant));
    }

    protected function performTaskToForgetCurrentTenant(): self
    {
        $this->tasksCollection->each(fn (SwitchTenantTask $task) => $task->forgetCurrent());

        return $this;
    }

    private function clearBoundCurrentTenant(): void
    {
        $containerKey = config('multitenancy.current_tenant_container_key');

        app()->forgetInstance($containerKey);
    }
}
