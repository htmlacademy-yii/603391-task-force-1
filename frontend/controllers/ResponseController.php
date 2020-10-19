<?php

namespace frontend\controllers;

use Exception;
use frontend\models\Response;
use TaskForce\Exception\TaskForceException;
use Yii;

class ResponseController extends SecureController
{

    /**
     * Confirm response by id
     *
     * @param int $id
     * @return string
     * @throws TaskForceException
     */
    public function actionConfirm(int $id)
    {
        $response = Response::findOrFail($id, "Отклик с ID #$id не найден");

        $task = $response->task;
        $transaction = Yii::$app->db->beginTransaction();

        $task->executor_id = $response['user_id'];
        $task->status = \TaskForce\Task::STATUS_IN_WORK;
        $response->status = Response::STATUS_CONFIRMED;

        try {
            $task->save();
            $response->save();
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw new TaskForceException("Не удалось назначить исполнителя отклика с ID #$id");
        }

        return $this->redirect(['/task/view', 'id' => $task->id]);
    }


    /**
     * Cancel response by id
     *
     * @param int $id
     * @return string
     * @throws TaskForceException
     */
    public function actionCancel(int $id)
    {
        $response = Response::findOrFail($id, "Отклик с ID #$id не найден");

        $task = $response->task;
        $response->status = Response::STATUS_CANCELED;

        try {
            $response->save();
        } catch (Exception $e) {
            throw new TaskForceException(
                "Ошибка отклонения отклика."
            );
        }

        return $this->redirect(['/task/view', 'id' => $task->id]);
    }
}
