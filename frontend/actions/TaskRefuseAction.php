<?php

namespace frontend\actions;

use frontend\models\Event;
use TaskForce\Actions\RefuseAction;
use TaskForce\EventEntity;
use TaskForce\Exception\TaskForceException;
use TaskForce\TaskEntity;
use Yii;
use yii\base\Action;

class TaskRefuseAction extends Action
{
    const TASK_REFUSED = 'Задача отклонена';

    /**
     * Refuse Action
     * @param int $id
     * @return string
     * @throws TaskForceException
     */
    public function run(int $id)
    {
        $task = new TaskEntity($id);
        if (Yii::$app->request->getIsPost()
            && $task->applyAction(RefuseAction::class)) {
            $event = new EventEntity(EventEntity::GROUP_TASK_ID);
            $event->user_id = $task->getOwnerUserId();
            $event->task_id = $id;
            $event->info = self::TASK_REFUSED;
            Event::createNotification($event);
            Yii::$app->session->setFlash('failure', self::TASK_REFUSED);
            $this->controller->goHome();
        }

        return $this->controller->redirect(['task/view', 'id' => $task->model->id]);
    }
}