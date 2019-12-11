<?php

namespace TaskForce;
use TaskForce\Actions;


class Task
{
    const ROLE_CONSUMER = 'Consumer';
    const ROLE_EXECUTOR = 'Executor';

    const ROLES = [self::ROLE_CONSUMER, self::ROLE_EXECUTOR];

    const ACTION_CANCEL = Actions\CancelAction::class;
    const ACTION_ASSIGN = Actions\AssignAction::class;
    const ACTION_DONE = Actions\CompleteAction::class;
    const ACTION_REFUSE = Actions\RefuseAction::class;
    const ACTION_RESPOND = Actions\RespondAction::class;

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
            'from' => self::STATUS_NEW, 'to' => self::STATUS_IN_WORK, 'role' => self::ROLE_CONSUMER
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

    private $executorID;
    private $customerID;
    private $deadLine;
    private $status;


    public function __construct(int $executorID, int $customerID, \DateTime $deadLine, string $status = self::STATUS_NEW)
    {
        $this->executorID = $executorID;
        $this->customerID = $customerID;
        $this->deadLine = $deadLine;
        $this->status = $status;
    }

    static function getAllStatuses() : array
    {
        return self::STATUSES;
    }

    static function getAllActions() : array
    {
        return self::ACTIONS;
    }

    private function getCurrentRole(int $id) : string {
        if ($id === $this->executorID) {
            return self::ROLE_EXECUTOR;
        } else if ($id === $this->customerID) {
            return self::ROLE_CONSUMER;
        } else {
            throw new \Exception('Can not get current role');
        }
    }

    public function getAvailableActions (int $currentUserId) : array
    {
        $array = [];

        foreach (ACTIONS as $item) {
            if ($item::checkPermission($this->getCurrentRole($currentUserId), $this->status)) {
                array_push($array, new $item);
            }
        }

        if (empty($array))  {
            throw new \Exception('Next statuses cannot be determined');
        };
        return $array;

    }

    public function getNextStatus(string $action, string $role) : string
    {
        if ($action::checkPermission($role, $this->status)) {
            $this->status = $action::getName();
        };

        throw new \Exception('Next status cannot be determined');
    }
}

