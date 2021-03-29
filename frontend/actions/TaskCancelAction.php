<?php

namespace frontend\actions;

use TaskForce\Actions\CancelAction;
use TaskForce\Exception\TaskForceException;
use TaskForce\TaskEntity;
use Yii;
use yii\base\Action;
use yii\web\NotFoundHttpException;

class TaskCancelAction extends Action
{
    const TASK_CANCELED = 'Задача отклонена';

    /**
     * @param int $id
     * @return string
     * @throws TaskForceException
     * @throws NotFoundHttpException
     */
    public function run(int $id)
    {
        $task = new TaskEntity($id);
        if (Yii::$app->request->getIsPost()
            && $task->applyAction(action: CancelAction::class)) {
            Yii::$app->session->setFlash(key: 'failure', value: self::TASK_CANCELED);
            $this->controller->goHome();
        }

        return $this->controller->redirect(['tasks/view', 'id' => $task->model->id]);
    }
}