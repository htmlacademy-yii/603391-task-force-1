<?php

namespace TaskForce;

use TaskForce\Exception\TaskForceException;

/**
 * Class Task
 * @package TaskForce
 */
class EventEntity
{
    const GROUP_MESSAGE_ID = 1;
    const GROUP_TASK_ID = 2;
    const GROUP_REVIEW_ID = 3;
    const GROUPS = [self::GROUP_MESSAGE_ID, self::GROUP_TASK_ID, self::GROUP_REVIEW_ID];

    public ?int $group_id = null;
    public ?int $user_id = null;
    public ?int $task_id = null;
    public ?string $info = '';

    /**
     * EventEntity constructor.
     * @param int $group
     * @throws TaskForceException
     */
    public function __construct(int $group)
    {
        if (!in_array($group, self::GROUPS)) {
            throw new TaskForceException('Не верный тип события.');
        }
        $this->group_id = $group;
    }
}