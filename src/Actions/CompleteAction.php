<?php

namespace TaskForce\Actions;

use TaskForce\Task;

class CompleteAction extends AbstractAction
{
    public static function getTitle(): string
    {
        return 'Complete';
    }

    public static function getName(): string
    {
        return self::class;
    }

    public static function isAllowed(string $role, string $status): bool
    {
        return ($role === Task::ROLE_CONSUMER && $status === Task::STATUS_IN_WORK);
    }

}
