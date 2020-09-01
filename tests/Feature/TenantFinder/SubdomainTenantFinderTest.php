<?php declare(strict_types=1);

namespace Enlight\Multitenancy\Tests\Feature\TenantFinder;

use Illuminate\Http\Request;
use Enlight\Multitenancy\Models\Tenant;
use Enlight\Multitenancy\Tests\TestCase;
use Enlight\Multitenancy\TenantFinder\SubdomainTenantFinder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SubdomainTenantFinderTest extends TestCase
{
    private SubdomainTenantFinder $tenantFinder;

    public function setUp(): void
    {
        parent::setUp();

        $this->tenantFinder = new SubdomainTenantFinder();
    }

    /** @test */
    public function it_throws_exception_if_request_doesnt_have_subdomain()
    {
        $this->expectException(NotFoundHttpException::class);

        $request = Request::create('https://landlord.domain');

        $this->tenantFinder->findForRequest($request)->id;
    }

    /** @test */
    public function it_can_find_a_tenant_for_the_current_subdomain_if_requested_from_landlord_domain()
    {
        $tenant = factory(Tenant::class)->create(['subdomain' => 'any-subdomain']);

        $request = Request::create('https://any-subdomain.landlord.domain');

        $this->assertEquals($tenant->id, $this->tenantFinder->findForRequest($request)->id);
    }

    /** @test */
    public function it_will_return_null_if_is_no_tenant_for_sudomain_if_requested_from_landlord_domain()
    {
        $request = Request::create('https://any-subdomain.landlord.domain');

        $this->assertNull($this->tenantFinder->findForRequest($request));
    }

    /** @test */
    public function it_will_throw_exception_if_requested_from_non_landlord_domain_with_subdomain()
    {
        $this->expectException(NotFoundHttpException::class);
        $request = Request::create('https://any-subdomain.non-landlord.domain');

        $this->assertNull($this->tenantFinder->findForRequest($request));
    }

    /** @test */
    public function it_will_throw_exception_if_subdomain_is_excluded_and_if_requested_from_landlord_domain()
    {
        $excludedSubdomains = [
            'www',
            'excluded-domain',
        ];

        config(['multitenancy.excluded_domains' => $excludedSubdomains]);

        $this->expectException(NotFoundHttpException::class);

        $request = Request::create("https://{$excludedSubdomains[0]}.landlord.domain");

        $this->tenantFinder->findForRequest($request);
    }
}
