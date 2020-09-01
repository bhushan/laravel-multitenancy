<?php declare(strict_types=1);

namespace Enlight\Multitenancy\Tests\TestClasses;

use Illuminate\Foundation\Auth\User as BaseUser;
use Enlight\Multitenancy\Models\Concerns\UsesTenantConnection;

class User extends BaseUser
{
    use UsesTenantConnection;
}
