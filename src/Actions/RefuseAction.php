<?php

namespace TaskForce\Actions;
use TaskForce\Task;

class RefuseAction extends AbstractAction
{
    public static function getTitle() : string
    {
        return 'Refuse';
    }

    public static function getName() : string
    {
        return self::class;
    }

    public static function isAllowed(string $role, string $status) : bool
    {
        return ($role === Task::ROLE_EXECUTOR  &&   $status === Task::STATUS_IN_WORK);
    }

}
