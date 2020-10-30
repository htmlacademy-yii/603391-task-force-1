<?php

namespace frontend\controllers;

use Exception;
use frontend\models\Category;
use frontend\models\forms\CompleteTaskForm;
use frontend\models\forms\CreateTaskForm;
use frontend\models\forms\ResponseTaskForm;
use frontend\models\Response;
use TaskForce\Actions\CancelAction;
use TaskForce\Actions\CompleteAction;
use TaskForce\Actions\RefuseAction;
use TaskForce\Actions\ResponseAction;
use TaskForce\Constant\UserRole;
use TaskForce\Exception\FileException;
use TaskForce\Exception\TaskForceException;
use TaskForce\Rule\CustomerAccessRule;
use TaskForce\Rule\ExecutorAccessRule;
use TaskForce\TaskEntity;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\UploadedFile;

class TaskController extends Controller
{
    public function behaviors()
    {
        return [
            'accessCustomer' => [
                'class' => AccessControl::class,
                'rules' => ['actions' => ['create', 'cancel', 'complete']],
                'ruleConfig' => ['class' => CustomerAccessRule::class],
            ],
            'accessExecutor' => [
                'class' => AccessControl::class,
                'only' => ['update'],
                'rules' => ['actions' => ['response', 'refuse']],
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

        if ($request = Yii::$app->request->post()) {
            $createTaskForm->load($request);
            $createTaskForm->files = UploadedFile::getInstances($createTaskForm, 'files');

            if ($createTaskForm->validate()) {
                $taskId = $createTaskForm->saveData($userId);

                if ($taskId) {
                    Yii::$app->session->setFlash('success', 'Задача создана');
                    $this->redirect('/tasks/view/' . $taskId);
                } else {
                    $createTaskForm->addError('', 'Задача не создана, попробуйте позже.');
                }
            }
        }

        $categories = Category::all();

        return $this->render('create', compact('createTaskForm', 'categories'));
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws TaskForceException
     * @throws \Throwable
     */
    public function actionResponse(int $id)
    {
        $task = new TaskEntity($id);

        $existResponse = Response::findResponsesByTaskIdUserId($id, Yii::$app->user->getId());
        if ($existResponse) {
            Yii::$app->session->setFlash('success', "Отклик уже сущестует");

            return $this->redirect(['tasks/view', 'id' => $id]);
        }

        $responseTaskForm = new ResponseTaskForm();
        if (Yii::$app->request->getIsPost()) {
            $responseTaskForm->load(Yii::$app->request->post());

            if ($responseTaskForm->validate() && in_array(ResponseAction::getTitle(), $task->getAvailableActions())) {
                $responseTaskForm->createResponse($id, Yii::$app->user->getId());
            }
        }

        return $this->redirect(['tasks/view', 'id' => $id]);
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

        return $this->redirect(['tasks/view', 'id' => $task->model->id]);
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

        return $this->redirect(['tasks/view', 'id' => $task->model->id]);
    }


    /**
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionComplete(int $id)
    {
        $task = new TaskEntity($id);
        $completeTaskForm = new CompleteTaskForm();
        if (Yii::$app->request->getIsPost()) {
            $completeTaskForm->load(Yii::$app->request->post());
            $completeTaskForm->validate();
            if ($completeTaskForm->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $task->applyAction(CompleteAction::class);
                    $task->createOpinion($completeTaskForm);
                    $transaction->commit();
                    $this->goHome();
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->redirect(['tasks/view', 'id' => $id]);
    }

}
