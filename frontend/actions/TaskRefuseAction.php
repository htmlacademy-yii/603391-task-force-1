<?php

namespace frontend\actions;

use frontend\models\Event;
use TaskForce\Actions\RefuseAction;
use TaskForce\Constant\NotificationType;
use TaskForce\Exception\TaskForceException;
use TaskForce\TaskEntity;
use Yii;
use yii\base\Action;
use yii\web\NotFoundHttpException;

class TaskRefuseAction extends Action
{
    const TASK_REFUSED = 'Задача отклонена';

    /**
     * Refuse Action
     * @param int $id
     * @return \yii\web\Response
     * @throws TaskForceException|NotFoundHttpException
     */
    public function run(int $id)
    {
        $task = new TaskEntity($id);
        if (Yii::$app->request->getIsPost()
            && $task->applyAction(RefuseAction::class)) {
            $event = new Event();
            $event->user_id = $task->getCustomerUserId();
            $event->task_id = $id;
            $event->info = self::TASK_REFUSED;
            $event->create(typeId: NotificationType::TASK_ACTIONS);
            Yii::$app->session->setFlash(key:'failure', value: self::TASK_REFUSED);
            $this->controller->goHome();
        }

        return $this->controller->redirect(['task/view', 'id' => $task->model->id]);
    }
}