<?php

namespace TaskForce\Actions;

use TaskForce\Profile;
use TaskForce\Role;
use TaskForce\Task;

class RefuseAction extends AbstractAction
{
    private const TITLE = 'Refuse';

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
        return (!$isOwner
            && $role === Role::EXECUTOR
            && $status === Task::STATUS_IN_WORK);
    }

}
