<?php declare(strict_types=1);

namespace Enlight\Multitenancy\TenantFinder;

use Illuminate\Http\Request;
use Enlight\Multitenancy\Models\Tenant;
use Enlight\Multitenancy\Models\Concerns\UsesTenantModel;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SubdomainOrDomainTenantFinder extends TenantFinder
{
    use UsesTenantModel;

    protected array $hostChunks;

    protected string $subdomain;

    protected array $landlordUrlChunks;

    public function findForRequest(Request $request): ?Tenant
    {
        $host = $request->getHost();
        $this->hostChunks = explode('.', $host);

        if (! $this->subdomainAvailable()) {
            return $this->getTenantModel()::whereDomain($host)->first();
        }

        $this->findSubdomain()->insureLandlordRequest();

        if ($this->excludedSubdomain()) {
            // www.google.com/register to google.com/register
            throw new NotFoundHttpException('Not Prepared to handle it right now');
            // return null;
        }

        return $this->getTenantModel()::whereSubdomain($this->subdomain)->first();
    }

    private function subdomainAvailable(): bool
    {
        return count($this->hostChunks) === 3;
    }

    private function findSubdomain(): self
    {
        $this->subdomain = array_shift($this->hostChunks);

        return $this;
    }

    private function insureLandlordRequest(): self
    {
        $this->landlordUrlChunks = explode('.', config('multitenancy.landlord_url'));

        if ($this->hostChunks !== $this->landlordUrlChunks) {
            throw new NotFoundHttpException('Not Prepared to handle it right now');
        }

        return $this;
    }

    private function excludedSubdomain(): bool
    {
        return in_array($this->subdomain, config('multitenancy.excluded_subdomains'));
    }
}
