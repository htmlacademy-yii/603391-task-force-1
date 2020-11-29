<?php

namespace frontend\controllers;

use Exception;
use frontend\models\Category;
use frontend\models\City;
use frontend\models\forms\CompleteTaskForm;
use frontend\models\forms\CreateTaskForm;
use frontend\models\forms\ResponseTaskForm;
use frontend\models\Response;
use TaskForce\Actions\CancelAction;
use TaskForce\Actions\CompleteAction;
use TaskForce\Actions\FailedAction;
use TaskForce\Actions\RefuseAction;
use TaskForce\Actions\ResponseAction;
use TaskForce\Exception\FileException;
use TaskForce\Exception\TaskForceException;
use TaskForce\Rule\CustomerAccessRule;
use TaskForce\Rule\ExecutorAccessRule;
use TaskForce\TaskEntity;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\UploadedFile;

class TaskController extends SecureController
{
    const TASKS_VIEW = 'tasks/view';

    /**
     * @return array|array[]
     */
    public function behaviors()
    {
        $customerActions = ['create', 'cancel', 'complete'];
        $executorActions = ['response', 'refuse'];

        return [
            'accessCustomer' => [
                'class' => AccessControl::class,
                'only' => $customerActions,
                'rules' => [
                    ['actions' => $customerActions],
                ],
                'ruleConfig' => ['class' => CustomerAccessRule::class],
            ],
            'accessExecutor' => [
                'class' => AccessControl::class,
                'only' => $executorActions,
                'rules' => [
                    ['actions' => $executorActions],
                ],
                'ruleConfig' => ['class' => ExecutorAccessRule::class],
            ],
        ];
    }

    /**
     * @return string
     * @throws FileException
     */
    public function actionCreate(): string
    {
        $userId = Yii::$app->user->getId();
        $createTaskForm = new CreateTaskForm();
        $cities = City::getList();

        if ($request = Yii::$app->request->post()) {
            $createTaskForm->load($request);
            $createTaskForm->files = UploadedFile::getInstances($createTaskForm, 'files');
            if ($createTaskForm->validate()) {
                $taskId = $createTaskForm->saveData($userId);
                if ($taskId) {
                    $this->redirect(self::TASKS_VIEW . '/' . $taskId);
                } else {
                    $createTaskForm->addError('', 'Задача не создана, попробуйте позже.');
                }
            }
        }
        $categories = Category::all();

        return $this->render('create', compact('createTaskForm', 'categories', 'cities'));
    }

    /**
     * @param int $taskId
     * @return \yii\web\Response
     * @throws TaskForceException
     * @throws \Throwable
     */
    public function actionResponse(int $taskId)
    {
        $task = new TaskEntity($taskId);
        $existResponse = Response::findByTaskIdCurrentUserId($taskId);
        if ($existResponse) {
            Yii::$app->session->setFlash('success', "Отклик уже сущестует");

            return $this->redirect([self::TASKS_VIEW, 'id' => $taskId]);
        }

        $responseTaskForm = new ResponseTaskForm();
        if ($post = Yii::$app->request->post()) {
            $responseTaskForm->load($post);
            if ($responseTaskForm->validate() && in_array(ResponseAction::getTitle(), $task->getAvailableActions())) {
                $responseTaskForm->createResponse($taskId);
            }
        }

        return $this->redirect([self::TASKS_VIEW, 'id' => $taskId]);
    }

    /**
     * Refuse Action
     * @param int $id
     * @return string
     * @throws TaskForceException
     */
    public function actionRefuse(int $id)
    {
        $task = new TaskEntity($id);
        if (Yii::$app->request->getIsPost()
            && $task->applyAction(RefuseAction::class)) {
            Yii::$app->session->setFlash('failure', 'Задача отклонена');
            $this->goHome();
        }

        return $this->redirect([self::TASKS_VIEW, 'id' => $task->model->id]);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws TaskForceException
     */
    public function actionCancel(int $id)
    {
        $task = new TaskEntity($id);
        if (Yii::$app->request->getIsPost()
            && $task->applyAction(CancelAction::class)) {
            Yii::$app->session->setFlash('failure', 'Задача отклонена');
            $this->goHome();
        }

        return $this->redirect([self::TASKS_VIEW, 'id' => $task->model->id]);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionComplete(int $id)
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
                    $this->goHome();
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->redirect([self::TASKS_VIEW, 'id' => $id]);
    }
}
