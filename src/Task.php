<?php

namespace TaskForce;

use frontend\models\Profile;
use TaskForce\Actions;
use TaskForce\Exception\TaskForceException;
use Yii;


class Task
{

    public const ACTION_CANCEL = Actions\CancelAction::class;
    public const ACTION_ASSIGN = Actions\AssignAction::class;
    public const ACTION_COMPLETE = Actions\CompleteAction::class;
    public const ACTION_REFUSE = Actions\RefuseAction::class;
    public const ACTION_RESPOND = Actions\RespondAction::class;

    public const ACTIONS = [self::ACTION_CANCEL, self::ACTION_ASSIGN, self::ACTION_COMPLETE, self::ACTION_REFUSE,
        self::ACTION_RESPOND];

    public const STATUS_NEW = 'New';
    public const STATUS_CANCEL = 'Cancel';
    public const STATUS_IN_WORK = 'In work';
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

    /**
     * @return array|string[]
     */
    public static function getAllStatuses(): array
    {
        return self::STATUSES;
    }

    /**
     * @return array|string[]
     */
    public static function getAllActions(): array
    {
        return self::ACTIONS;
    }

    /**
     * @param int $id
     * @return string
     * @throws TaskForceException
     */
    public function getCurrentRole(int $id): string
    {
        $role = Profile::findProfileByUserId($id)['role'];
        if (!$role) {
            throw new TaskForceException('Can not get current role');
        }

        return $role;
    }

    /**
     * @param int $currentUserId
     * @return array
     * @throws TaskForceException
     */
    public function getAvailableActions(int $currentUserId): array
    {
        $availableActions = [];
        $isOwner = ($currentUserId ===  $this->customerID);
        $role = $this->getCurrentRole($currentUserId);
        foreach (self::ACTIONS as $action) {
            if ($action::isAllowed($isOwner, $this->status, $role)) {
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
        if (!in_array($role, Role::LIST, true)) {
            throw new TaskForceException('Unknown role ' . $role);
        }

        $currentUserId = Yii::$app->user->getId();
        $isOwner = ($currentUserId === $this->customerID);
        if ($action::isAllowed($isOwner, $this->status, $role)) {

            return self::ACTION_TO_STATUS[$action];

        };
        throw new TaskForceException('Can not get next status.');
    }
}

