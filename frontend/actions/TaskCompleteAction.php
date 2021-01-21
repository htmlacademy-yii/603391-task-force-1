<?php

namespace frontend\actions;

use frontend\models\Event;
use frontend\models\forms\CompleteTaskForm;
use TaskForce\Actions\CompleteAction;
use TaskForce\Actions\FailedAction;
use TaskForce\EventEntity;
use TaskForce\Exception\TaskForceException;
use TaskForce\TaskEntity;
use Yii;
use yii\base\Action;
use yii\db\Exception;

class TaskCompleteAction extends Action
{
    /**
     * @param int $id
     * @return string
     * @throws TaskForceException
     */
    public function run(int $id)
    {
        $task = new TaskEntity($id);
        $completeTaskForm = new CompleteTaskForm();
        if ($post = Yii::$app->request->post()) {
            $completeTaskForm->load($post);
            if ($completeTaskForm->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $completion = ($completeTaskForm->completion === $completeTaskForm::VALUE_YES);
                    if ($completion) {
                        $task->applyAction(CompleteAction::class);
                    } else {
                        $task->applyAction(FailedAction::class);
                    }
                    $task->createOpinion($completeTaskForm);
                    $transaction->commit();

                    $event = new EventEntity(EventEntity::GROUP_TASK_ID);
                    $event->user_id = $task->getAssistUserId();
                    $event->task_id = $id;
                    $event->info = 'Завершение задания';
                    Event::createNotification($event);

                    $this->controller->goHome();
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->controller->redirect(['tasks/view', 'id' => $id]);
    }
}