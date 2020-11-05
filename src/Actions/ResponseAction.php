<?php

namespace TaskForce\Actions;

use TaskForce\Constant\UserRole;
use TaskForce\TaskEntity;

class ResponseAction extends AbstractAction
{
    private const TITLE = 'Response';

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
            && $role === UserRole::EXECUTOR
            && $status === TaskEntity::STATUS_NEW);
    }

}

