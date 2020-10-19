<?php

namespace TaskForce\Actions;

use TaskForce\Role;
use TaskForce\Task;

class CompleteAction extends AbstractAction
{
    private const TITLE = 'Complete';

    public static function getTitle(): string
    {
        return self::TITLE;
    }

    public static function getName(): string
    {
        return self::class;
    }

    public static function isAllowed(bool $isOwner, string $status, string $role): bool
    {
        return ($isOwner && $role === Role::CUSTOMER && $status === Task::STATUS_IN_WORK);
    }

}
