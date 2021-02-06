<?php

namespace TaskForce;

use Exception;
use frontend\models\Response;
use TaskForce\Constant\UserRole;
use TaskForce\Exception\TaskForceException;
use Yii;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

class ResponseEntity
{
    public const STATUS_NEW = 'new';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CANCELED = 'canceled';

    public const LIST = [self::STATUS_NEW, self::STATUS_CONFIRMED, self::STATUS_CANCELED];

    public ?ActiveRecord $model;
    private ?ActiveRecord $modelTask;

    /**
     * ResponseEntity constructor.
     * @param int $id
     * @throws NotFoundHttpException
     */
    public function __construct(int $id)
    {
        $this->model = Response::findOrFail($id, "Отклик с ID #$id не найден");
        $this->modelTask = $this->model->task;
    }

    /**
     * @return bool
     */
    private function isCustomer(): bool
    {
        if (Yii::$app->user->identity->role === UserRole::CUSTOMER) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    private function isNewTaskStatus(): bool
    {
        return ($this->modelTask->status === TaskEntity::STATUS_NEW);
    }

    /**
     * @return bool
     */
    private function isNewStatus(): bool
    {
        return ($this->model->status === self::STATUS_NEW);
    }

    /**
     * @return bool
     */
    private function isAllowAction(): bool
    {
        return ($this->isCustomer() && $this->isNewTaskStatus() && $this->isNewStatus());
    }

    /**
     * @return bool
     * @throws TaskForceException
     */
    public function confirm(): bool
    {
        if ($this->isAllowAction()) {
            $transaction = Yii::$app->db->beginTransaction();

            $this->modelTask->executor_id = $this->model->user_id;
            $this->modelTask->status = TaskEntity::STATUS_IN_WORK;
            $this->model->status = self::STATUS_CONFIRMED;

            try {
                $this->modelTask->save();
                $this->model->save();
                $transaction->commit();
                return true;
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new TaskForceException("Failed to confirm response." . $e->getMessage());
            }
        }

        return false;
    }

    /**
     * @return bool
     * @throws TaskForceException
     */
    public function cancel(): bool
    {
        if ($this->isAllowAction()) {
            $this->model->status = self::STATUS_CANCELED;
            try {
                $this->model->save();
                return true;
            } catch (Exception $e) {
                throw new TaskForceException("Failed to cancel response. " . $e->getMessage());
            }
        }

        return false;
    }
}
