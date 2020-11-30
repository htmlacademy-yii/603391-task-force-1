<?php

namespace TaskForce\Actions;

use TaskForce\Constant\UserRole;
use TaskForce\TaskEntity;

class CancelAction extends AbstractAction
{
    private const TITLE = 'Cancel';

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
        return ($isOwner && $role === UserRole::CUSTOMER && $status === TaskEntity::STATUS_NEW);
    }

}

