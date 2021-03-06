<?php declare(strict_types=1);

namespace Enlight\Multitenancy\Tests\Feature\Http\Middleware;

use Illuminate\Support\Facades\Route;
use Enlight\Multitenancy\Models\Tenant;
use Enlight\Multitenancy\Tests\TestCase;
use Enlight\Multitenancy\Exceptions\NoCurrentTenant;
use Enlight\Multitenancy\Http\Middleware\NeedsTenant;

class NeedsTenantTest extends TestCase
{
    private Tenant $tenant;

    public function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        Route::get('middleware-test', fn () => 'ok')->middleware(NeedsTenant::class);

        $this->tenant = factory(Tenant::class)->create();
    }

    /** @test */
    public function it_will_pass_if_there_is_current_tenant_set()
    {
        $this->tenant->makeCurrent();

        $this->get('middleware-test')->assertOk();
    }

    /** @test */
    public function it_will_throw_an_exception_when_there_is_not_current_tenant()
    {
        $this->expectException(NoCurrentTenant::class);

        $this->get('middleware-test');
    }
}
