<?php

namespace TaskForce\Constant;

use TaskForce\TaskEntity;

class MyTask
{

    public const FILTER_COMPLETED = 'completed';
    public const FILTER_NEW = 'new';
    public const FILTER_ACTIVE = 'active';
    public const FILTER_CANCELED = 'canceled';
    public const FILTER_FAILED = 'hidden';

    public const FILTER_NAME_DONE = 'Завершенные';
    public const FILTER_NAME_NEW = 'Новые';
    public const FILTER_NAME_ACTIVE = 'Активные';
    public const FILTER_NAME_CANCELED = 'Отмененые';
    public const FILTER_NAME_FAILED = 'Просроченые';

    const STATUS_BY_FILTER = [
        MyTask::FILTER_COMPLETED => TaskEntity::STATUS_COMPLETE,
        MyTask::FILTER_NEW => TaskEntity::STATUS_NEW,
        MyTask::FILTER_ACTIVE => TaskEntity::STATUS_IN_WORK,
        MyTask::FILTER_CANCELED => TaskEntity::STATUS_CANCEL,
        MyTask::FILTER_FAILED => TaskEntity::STATUS_FAILED,
    ];

    public const FILTERS = [
        self::FILTER_COMPLETED,
        self::FILTER_NEW,
        self::FILTER_ACTIVE,
        self::FILTER_CANCELED,
        self::FILTER_FAILED
    ];

    public string $currentFilter =  self::FILTER_COMPLETED;

    public static function getName()
    {
        return [
            self::FILTER_COMPLETED => self::FILTER_NAME_DONE,
            self::FILTER_NEW => self::FILTER_NAME_NEW,
            self::FILTER_ACTIVE => self::FILTER_NAME_ACTIVE,
            self::FILTER_CANCELED => self::FILTER_NAME_CANCELED,
            self::FILTER_FAILED => self::FILTER_NAME_FAILED,
        ];
    }
}
