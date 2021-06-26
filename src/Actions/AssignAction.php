<?php

namespace TaskForce\Actions;

use TaskForce\Constant\UserRole;
use TaskForce\TaskEntity;

class AssignAction extends AbstractAction
{
    private const TITLE = 'Assign';

    /**
     * @return string
     */
    public static function getTitle(): string
    {
        return self::TITLE;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return self::class;
    }

    /**
     * @param bool $isOwner
     * @param string $status
     * @param string $role
     * @return bool
     */
    public static function isAllowed(bool $isOwner, string $status, string $role): bool
    {
        return ($isOwner && $role === UserRole::CUSTOMER && $status === TaskEntity::STATUS_NEW);
    }

}

