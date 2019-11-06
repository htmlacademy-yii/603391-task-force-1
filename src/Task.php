<?php


namespace TaskForce;


class Task
{
    const ROLE_CONSUMER = 'Consumer';
    const ROLE_EXECUTOR = 'Executor';

    const ROLES = [self::ROLE_CONSUMER, self::ROLE_EXECUTOR];

    const ACTION_CANCEL = 'Cancel';
    const ACTION_ASSIGN = 'Assign';
    const ACTION_DONE = 'Done';
    const ACTION_REFUSE = 'Refuse';
    const ACTION_RESPOND = 'Respond';

    const ACTIONS = [self::ACTION_CANCEL, self::ACTION_ASSIGN, self::ACTION_DONE, self::ACTION_REFUSE,
        self::ACTION_RESPOND];

    const STATUS_NEW = 'New';
    const STATUS_CANCEL = 'Cancel';
    const STATUS_IN_WORK = 'In_work';
    const STATUS_DONE = 'Done';
    const STATUS_FAILED = 'Failed';

    const STATUSES = [self::STATUS_NEW, self::STATUS_CANCEL, self::STATUS_IN_WORK, self::STATUS_DONE,
        self::STATUS_FAILED];


    // массив действий(переходов) из статуса в статус для определенных ролей
    const CONVERSIONS = [
        [
            'name' => self::ACTION_CANCEL,
            'from' => self::STATUS_NEW, 'to' => self::STATUS_CANCEL, 'role' => self::ROLE_CONSUMER
        ],
        [
            'name' => self::ACTION_RESPOND,
            'from' => self::STATUS_NEW, 'to' => self::STATUS_IN_WORK, 'role' => self::ROLE_EXECUTOR
        ],
        [
            'name' => self::ACTION_ASSIGN,
            'from' => self::STATUS_NEW, 'to' => self::STATUSES['IN_WORK'], 'role' => self::ROLE_CONSUMER
        ],
        [
            'name' => self::ACTION_DONE,
            'from' => self::STATUS_IN_WORK, 'to' => self::STATUS_DONE, 'role' => self::ROLE_CONSUMER
        ],
        [
            'name' => self::ACTION_REFUSE,
            'from' => self::STATUS_IN_WORK, 'to' => self::STATUS_FAILED, 'role' => self::ROLE_EXECUTOR
        ],
    ];

    private $performerID;
    private $customerID;
    private $activeStatus;
    private $deadLine;
    private $status;

    public function __construct($performerID, $customerID, $deadLine, $status = self::STATUS_NEW)
    {
        $this->performerID = $performerID;
        $this->customerID = $customerID;
        $this->deadLine = $deadLine;
        $this->status = $status;
    }

    public function getAllStatuses()
    {
        return self::STATUSES;
    }

    public function getAllActions()
    {
        return self::ACTIONS;
    }

    public function setNextStatus($action, $role)
    {
        $next = '';
        foreach (self::CONVERSIONS as $item) {
            if ($item['from'] == $this->activeStatus
                && $item['name'] == $action
                && $item['role'] == $role) {
                $next = $item['to'];
            }
        }
        return $next;
    }

}
