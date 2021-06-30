<?php

namespace frontend\actions;

use frontend\models\Event;
use frontend\models\forms\ResponseTaskForm;
use frontend\models\Response;
use TaskForce\Actions\ResponseAction;
use TaskForce\Constant\NotificationType;
use TaskForce\TaskEntity;
use Yii;
use yii\base\Action;

class TaskResponseAction extends Action
{
    const NEW_REVIEW = 'Новый отклик к заданию';


    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws \TaskForce\Exception\TaskForceException
     * @throws \Throwable
     * @throws \yii\web\NotFoundHttpException
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
                $event = new Event();
                $event->user_id = $task->getCustomerUserId();
                $event->task_id = $id;
                $event->info = self::NEW_REVIEW;
                $event->create(NotificationType::NEW_REVIEW);
            }
        }

        return $this->controller->redirect(['tasks/view', 'id' => $id]);
    }
}