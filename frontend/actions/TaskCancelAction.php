<?php

namespace frontend\actions;

use frontend\models\Event;
use TaskForce\Actions\CancelAction;
use TaskForce\EventEntity;
use TaskForce\Exception\TaskForceException;
use TaskForce\TaskEntity;
use Yii;
use yii\base\Action;

class TaskCancelAction extends Action
{
    const TASK_CANCELED = 'Задача отклонена';

    /**
     * @param int $id
     * @return string
     * @throws TaskForceException
     */
    public function run(int $id)
    {
        $task = new TaskEntity($id);
        if (Yii::$app->request->getIsPost()
            && $task->applyAction(CancelAction::class)) {
            $event = new EventEntity(EventEntity::GROUP_TASK_ID);
            $event->user_id = $task->getAssistUserId();
            $event->task_id = $id;
            $event->info = self::TASK_CANCELED;
            Event::createNotification($event);
            Yii::$app->session->setFlash('failure', self::TASK_CANCELED);
            $this->controller->goHome();
        }

        return $this->controller->redirect(['tasks/view', 'id' => $task->model->id]);
    }
}