<?php

namespace TaskForce;

use TaskForce\Actions;
use TaskForce\Exception\TaskForceException;


class Task
{
    public const ROLE_CUSTOMER = 'customer';
    public const ROLE_EXECUTOR = 'executor';

    public const ROLES = [self::ROLE_CUSTOMER, self::ROLE_EXECUTOR];

    public const ACTION_CANCEL = Actions\CancelAction::class;
    public const ACTION_ASSIGN = Actions\AssignAction::class;
    public const ACTION_COMPLETE = Actions\CompleteAction::class;
    public const ACTION_REFUSE = Actions\RefuseAction::class;
    public const ACTION_RESPOND = Actions\RespondAction::class;

    public const ACTIONS = [self::ACTION_CANCEL, self::ACTION_ASSIGN, self::ACTION_COMPLETE, self::ACTION_REFUSE,
        self::ACTION_RESPOND];

    public const STATUS_NEW = 'New';
    public const STATUS_CANCEL = 'Cancel';
    public const STATUS_IN_WORK = 'In_work';
    public const STATUS_COMPLETE = 'Complete';
    public const STATUS_FAILED = 'Failed';

    public const ACTION_TO_STATUS = [
        self::ACTION_CANCEL => self::STATUS_CANCEL,
        self::ACTION_COMPLETE => self::STATUS_COMPLETE,
        self::ACTION_RESPOND => self::STATUS_IN_WORK,
        self::ACTION_ASSIGN => self::STATUS_IN_WORK,
        self::ACTION_REFUSE => self::STATUS_FAILED
    ];

    public const STATUSES = [self::STATUS_NEW, self::STATUS_CANCEL, self::STATUS_IN_WORK, self::STATUS_COMPLETE,
        self::STATUS_FAILED];

    private $executorID;
    private $customerID;
    private $deadLine;
    private $status;

    public function __construct(int $executorID, int $customerID, \DateTime $deadLine, string $status = self::STATUS_NEW)
    {
        if (!in_array($status, self::STATUSES, true)) {
            throw new TaskForceException('Unknown status ' . $status);
        }

        $this->executorID = $executorID;
        $this->customerID = $customerID;
        $this->deadLine = $deadLine;
        $this->status = $status;
    }

    public static function getAllStatuses(): array
    {
        return self::STATUSES;
    }

    public static function getAllActions(): array
    {
        return self::ACTIONS;
    }

    public function getCurrentRole(int $id): string
    {
        if ($id === $this->executorID) {
            return self::ROLE_EXECUTOR;
        };
        if ($id === $this->customerID) {
            return self::ROLE_CUSTOMER;
        }
        throw new TaskForceException('Can not get current role');
    }

    public function getAvailableActions(int $currentUserId): array
    {
        $availableActions = [];
        foreach (self::ACTIONS as $action) {
            if ($action::isAllowed($this->getCurrentRole($currentUserId), $this->status)) {
                array_push($availableActions, $action::getName());
            }
        }
        return $availableActions;
    }

    /**
     * @param string $action
     * @param string $role
     * @return string
     * @throws TaskForceException
     */
    public function getNextStatus(string $action, string $role): string
    {
        if (!in_array($action, self::ACTIONS, true)) {
            throw new TaskForceException('Unknown action' . $action);
        }
        if (!in_array($role, self::ROLES, true)) {
            throw new TaskForceException('Unknown role ' . $role);
        }

        if ($action::isAllowed($role, $this->status)) {
            return self::ACTION_TO_STATUS[$action];
        };
        throw new TaskForceException('Can not get next status ');
    }
}

