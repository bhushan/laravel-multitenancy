<?php declare(strict_types=1);

namespace Enlight\Multitenancy\Exceptions;

use Exception;
use Enlight\Multitenancy\Tasks\SwitchTenantTask;

class TaskCannotBeExecuted extends Exception
{
    public static function make(SwitchTenantTask $task, string $reason): self
    {
        $taskClass = get_class($task);

        return new static("Task `{$taskClass}` could not be executed. Reason: {$reason}");
    }
}
