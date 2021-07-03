<?php

namespace TaskForce\Actions;

use TaskForce\Constant\UserRole;
use TaskForce\TaskEntity;

class RefuseAction extends AbstractAction
{
    private const TITLE = 'Refuse';

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
        return (!$isOwner
            && $role === UserRole::EXECUTOR
            && $status === TaskEntity::STATUS_IN_WORK);
    }

}
