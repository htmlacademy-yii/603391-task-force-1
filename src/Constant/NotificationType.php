<?php

namespace TaskForce\Constant;

class NotificationType
{
    public const NEW_MESSAGE = 1;
    public const TASK_ACTIONS = 2;
    public const NEW_REVIEW = 3;

    public const LIST = [self::NEW_MESSAGE, self::TASK_ACTIONS, self::NEW_REVIEW];
}