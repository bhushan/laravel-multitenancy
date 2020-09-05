<?php declare(strict_types=1);

use Enlight\Multitenancy\Models\Tenant;
use Enlight\Multitenancy\Tasks\PrefixCacheTask;
use Enlight\Multitenancy\Actions\MigrateTenantAction;
use Enlight\Multitenancy\Tasks\SwitchTenantDatabaseTask;
use Enlight\Multitenancy\Actions\MakeTenantCurrentAction;
use Enlight\Multitenancy\Actions\ForgetCurrentTenantAction;
use Enlight\Multitenancy\Actions\MakeQueueTenantAwareAction;
use Enlight\Multitenancy\TenantFinder\SubdomainOrDomainTenantFinder;

return [
    /*
     * This class is responsible for determining which tenant should be current
     * for the given request.
     *
     * This class should extend `Enlight\Multitenancy\TenantFinder\TenantFinder`
     *
     */
    'tenant_finder' => SubdomainOrDomainTenantFinder::class,

    /*
     * These fields are used by tenant:artisan command to match one or more tenant
     */
    'tenant_artisan_search_fields' => [
        'id',
    ],

    /*
     * These tasks will be performed when switching tenants.
     *
     * A valid task is any class that implements Enlight\Multitenancy\Tasks\SwitchTenantTask
     */
    'switch_tenant_tasks' => [
        PrefixCacheTask::class,
        SwitchTenantDatabaseTask::class,
    ],

    /*
     * This class is the model used for storing configuration on tenants.
     *
     * It must be or extend `Enlight\Multitenancy\Models\Tenant::class`
     */
    'tenant_model' => Tenant::class,

    /*
     * If there is a current tenant when dispatching a job, the id of the current tenant
     * will be automatically set on the job. When the job is executed, the set
     * tenant on the job will be made current.
     */
    'queues_are_tenant_aware_by_default' => true,

    /*
     * The connection name to reach the tenant database.
     *
     * Set to `null` to use the default connection.
     */
    'tenant_database_connection_name' => null,

    /*
     * The connection name to reach the landlord database
     */
    'landlord_database_connection_name' => 'landlord',

    /*
     * This key will be used to bind the current tenant in the container.
     */
    'current_tenant_container_key' => 'currentTenant',

    /*
     * You can customize some of the behavior of this package by using our own custom action.
     * Your custom action should always extend the default one.
     */
    'actions' => [
        'make_tenant_current_action' => MakeTenantCurrentAction::class,
        'forget_current_tenant_action' => ForgetCurrentTenantAction::class,
        'make_queue_tenant_aware_action' => MakeQueueTenantAwareAction::class,
        'migrate_tenant' => MigrateTenantAction::class,
    ],

    /*
     * You can exclude any subdomains pointing to our application.
     */
    'excluded_subdomains' => [
        'www',
    ],

    /*
     * It is required to be set to use subdomain.
     * Using subdomain strategy forces us to deploy this application on top level domain.
     *
     * Ex. enlight.host  <-- should be in this format only
     *
     * http://enlight.host <-- should not be like this
     * https://enlight.host <-- should not be like this
     * https://www.enlight.host <-- should not be like this
     * https://anysubdomain.enlight.host <-- should not be like this
     */
    'landlord_url' => env('LANDLORD_URL', 'landlord.test'),
];
