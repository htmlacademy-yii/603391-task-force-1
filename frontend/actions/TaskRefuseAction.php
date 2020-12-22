<?php

namespace frontend\actions;

use TaskForce\Actions\RefuseAction;
use TaskForce\Exception\TaskForceException;
use TaskForce\TaskEntity;
use Yii;
use yii\base\Action;

class TaskRefuseAction extends Action
{
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
            Yii::$app->session->setFlash('failure', 'Задача отклонена');
            $this->controller->goHome();
        }

        return $this->controller->redirect(['task/view', 'id' => $task->model->id]);
    }
}