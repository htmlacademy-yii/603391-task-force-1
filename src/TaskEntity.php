<?php

namespace TaskForce;

use frontend\models\forms\CompleteTaskForm;
use frontend\models\Opinion;
use frontend\models\Task;
use TaskForce\Actions;
use TaskForce\Constant\UserRole;
use TaskForce\Exception\TaskForceException;
use Throwable;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class Task
 * @package TaskForce
 */
class TaskEntity
{
    public const ACTION_CANCEL = Actions\CancelAction::class;
    public const ACTION_ASSIGN = Actions\AssignAction::class;
    public const ACTION_COMPLETE = Actions\CompleteAction::class;
    public const ACTION_REFUSE = Actions\RefuseAction::class;
    public const ACTION_RESPOND = Actions\ResponseAction::class;
    public const ACTION_FAILED = Actions\FailedAction::class;

    public const ACTIONS = [
        self::ACTION_CANCEL,
        self::ACTION_ASSIGN,
        self::ACTION_COMPLETE,
        self::ACTION_REFUSE,
        self::ACTION_RESPOND
    ];

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
        self::ACTION_REFUSE => self::STATUS_FAILED,
        self::ACTION_FAILED => self::STATUS_FAILED
    ];

    public const STATUS_TO_NAME = [
        self::STATUS_CANCEL => 'Отменено',
        self::STATUS_COMPLETE => 'Завершено',
        self::STATUS_IN_WORK => 'В работе',
        self::STATUS_NEW => 'Новое',
        self::STATUS_FAILED => 'Просрочено'
    ];

    public const STATUSES = [
        self::STATUS_NEW,
        self::STATUS_CANCEL,
        self::STATUS_IN_WORK,
        self::STATUS_COMPLETE,
        self::STATUS_FAILED
    ];

    public Task $model;
    private ?int $executorId;
    private ?int $customerId;
    private string $status;

    /**
     * TaskEntity constructor.
     * @param int $taskId
     * @throws NotFoundHttpException
     */
    public function __construct(int $taskId)
    {
        $this->model = Task::findOrFail($taskId, "Task with ID #$taskId not found.");
        $this->executorId = $this->model->executor_id;
        $this->customerId = $this->model->customer_id;
        $this->status = $this->model->status;
    }

    /**
     * @return array
     */
    public static function getAllStatuses(): array
    {
        return self::STATUSES;
    }

    /**
     * @return array
     */
    public static function getAllActions(): array
    {
        return self::ACTIONS;
    }

    /**
     * @return array
     */
    public function getAvailableActions(): array
    {
        $availableActions = [];
        $userId = Yii::$app->user->identity->getId();
        $isOwner = ($userId === $this->customerId);
        $currentRole = Yii::$app->user->identity->role;

        foreach (self::ACTIONS as $action) {
            if ($action::isAllowed($isOwner, $this->status, $currentRole)) {
                array_push($availableActions, $action::getTitle());
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
        if (!in_array($role, UserRole::LIST, true)) {
            throw new TaskForceException('Unknown role ' . $role);
        }

        $currentUserId = Yii::$app->user->getId();
        $isOwner = ($currentUserId === $this->customerId);
        if ($action::isAllowed($isOwner, $this->status, $role)) {
            return self::ACTION_TO_STATUS[$action];
        }
        throw new TaskForceException('Can not get next status.');
    }

    /**
     * @param string $action
     * @return bool
     * @throws TaskForceException
     */
    public function applyAction(string $action): bool
    {

        $currentUserId = Yii::$app->user->getId();
        $isOwner = ($currentUserId === $this->customerId);
        $currentRole = Yii::$app->user->identity->role;

        if ($action::isAllowed($isOwner, $this->status, $currentRole)) {
            $this->model->status = self::ACTION_TO_STATUS[$action];

            if ($this->model->save()) {
                return true;
            }
        }
        throw new TaskForceException("Can not apply $action.");
    }

    /**
     * @return int
     */
    public function getContractorUserId(): int
    {
        return (Yii::$app->user->identity->getId() == $this->model->customer_id && $this->model->executor_id)
            ? $this->model->executor_id : $this->model->customer_id;
    }

    /**
     * @return int
     */
    public function getExecutorUserId(): int
    {
        return $this->model->executor_id;
    }

    /**
     * @return int
     */
    public function getCustomerUserId(): int
    {
        return $this->model->customer_id;
    }

    /**
     * @param CompleteTaskForm $completeTaskForm
     * @return bool
     * @throws TaskForceException
     */
    public function createOpinion(CompleteTaskForm $completeTaskForm): bool
    {
        $opinion = new Opinion();
        $opinion->task_id = $this->model->id;
        $opinion->owner_id = $this->model->customer_id;
        $opinion->executor_id = $this->model->executor_id;
        $opinion->rate = $completeTaskForm->rating;
        $opinion->description = $completeTaskForm->comment;
        $opinion->done = ($completeTaskForm->completion === $completeTaskForm::VALUE_YES);

        try {
            $opinion->insert();
        } catch (Throwable $e) {
            throw new TaskForceException('Ошибка создания отзыва. ' . $e->getMessage());
        }

        return true;
    }
}
