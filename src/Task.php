<?php

namespace TaskForce;
use TaskForce\Actions;
use TaskForce\Exception\TaskForceException;


class Task
{
    const ROLE_CONSUMER = 'Consumer';
    const ROLE_EXECUTOR = 'Executor';

    const ROLES = [self::ROLE_CONSUMER, self::ROLE_EXECUTOR];

    const ACTION_CANCEL = Actions\CancelAction::class;
    const ACTION_ASSIGN = Actions\AssignAction::class;
    const ACTION_COMPLETE = Actions\CompleteAction::class;
    const ACTION_REFUSE = Actions\RefuseAction::class;
    const ACTION_RESPOND = Actions\RespondAction::class;

    const ACTIONS = [self::ACTION_CANCEL, self::ACTION_ASSIGN, self::ACTION_COMPLETE, self::ACTION_REFUSE,
        self::ACTION_RESPOND];

    const STATUS_NEW = 'New';
    const STATUS_CANCEL = 'Cancel';
    const STATUS_IN_WORK = 'In_work';
    const STATUS_COMPLETE = 'Complete';
    const STATUS_FAILED = 'Failed';

    const ACTION_TO_STATUS = [
        self::ACTION_CANCEL => self::STATUS_CANCEL,
        self::ACTION_COMPLETE => self::STATUS_COMPLETE,
        self::ACTION_RESPOND => self::STATUS_IN_WORK,
        self::ACTION_ASSIGN => self::STATUS_IN_WORK,
        self::ACTION_REFUSE => self::STATUS_FAILED
    ];

    const STATUSES = [self::STATUS_NEW, self::STATUS_CANCEL, self::STATUS_IN_WORK, self::STATUS_COMPLETE,
        self::STATUS_FAILED];

    private $executorID;
    private $customerID;
    private $deadLine;
    private $status;


    public function __construct(int $executorID, int $customerID, \DateTime $deadLine, string $status = self::STATUS_NEW)
    {
        //при передаче в конструктор класса имени статуса, в теле конструктора должна быть проверка,
        // что такой статус существует. В противном случае метод должен вызвать исключение.

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

    public function getCurrentRole(int $id) : string {
        if ($id === $this->executorID) {
            return self::ROLE_EXECUTOR;
        };
        if ($id === $this->customerID) {
            return self::ROLE_CONSUMER;
        }
          throw new TaskForceException('Can not get current role');

    }

    public function getAvailableActions (int $currentUserId) : array
    {
        //при передаче в конструктор класса имени статуса, в теле конструктора должна быть проверка, что такой статус
        // существует. В противном случае метод должен вызвать исключение.
        $availableActions = [];
        foreach (self::ACTIONS as $action) {
            if ($action::isAllowed($this->getCurrentRole($currentUserId), $this->status)) {
                array_push($availableActions, $action::getName());
            }
        }
        return $availableActions;

    }

    public function getNextStatus(string $action, string $role) : string
    {

        if ($action::isAllowed($role, $this->status)) {
            return  self::ACTION_TO_STATUS[$action];
        };
        throw new TaskForceException('Can not get next status');
    }
}

