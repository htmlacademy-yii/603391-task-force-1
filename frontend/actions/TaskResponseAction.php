<?php

namespace frontend\actions;

use frontend\models\Event;
use frontend\models\forms\ResponseTaskForm;
use frontend\models\Response;
use TaskForce\Actions\ResponseAction;
use TaskForce\EventEntity;
use TaskForce\Exception\TaskForceException;
use TaskForce\TaskEntity;
use Throwable;
use Yii;
use yii\base\Action;

class TaskResponseAction extends Action
{
    /**
     * @param int $id
     * @return string
     * @throws TaskForceException
     * @throws Throwable
     */
    public function run(int $id)
    {
        $task = new TaskEntity($id);
        $existResponse = Response::findByTaskIdCurrentUserId($id);
        if ($existResponse) {
            Yii::$app->session->setFlash('success', "Отклик уже сущестует");

            return $this->controller->redirect(['tasks/view', 'id' => $id]);
        }

        $responseTaskForm = new ResponseTaskForm();
        if ($post = Yii::$app->request->post()) {
            $responseTaskForm->load($post);
            if ($responseTaskForm->validate() && in_array(ResponseAction::getTitle(), $task->getAvailableActions())) {
                $responseTaskForm->createResponse($id);
                $event = new EventEntity(EventEntity::GROUP_REVIEW_ID);
                $event->user_id = $task->getAssistUserId();
                $event->task_id = $id;
                $event->info = 'Новый отклик к заданию';
                Event::createNotification($event);
            }
        }

        return $this->controller->redirect(['tasks/view', 'id' => $id]);
    }
}