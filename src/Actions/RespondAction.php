<?php

namespace TaskForce\Actions;

use TaskForce\Task;

class RespondAction extends AbstractAction
{
    private const TITLE = 'Respond';

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
            && $role === Task::ROLE_EXECUTOR
            && $status === Task::STATUS_NEW);
    }

}

