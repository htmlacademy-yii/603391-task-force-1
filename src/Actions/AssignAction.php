<?php

namespace TaskForce\Actions;

use TaskForce\Task;

class AssignAction extends AbstractAction
{
    public static function getTitle(): string
    {
        return 'Assign';
    }

    public static function getName(): string
    {
        return self::class;
    }

    public static function isAllowed(string $role, string $status): bool
    {
        return ($role === Task::ROLE_CONSUMER && $status === Task::STATUS_NEW);
    }

}

