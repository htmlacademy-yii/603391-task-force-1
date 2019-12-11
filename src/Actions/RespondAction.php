<?php

namespace TaskForce\Actions;
use TaskForce\Task;

class RespondAction extends AbstractAction
{

    public static function getTitle() : string
    {
        return 'Respond';
    }

    public static function getName() : string
    {
        return self::class;
    }

    public static function isAllowed(string $role, string $status) : bool
    {
        return ($role === Task::ROLE_EXECUTOR && $status === Task::STATUS_NEW);
    }

}

