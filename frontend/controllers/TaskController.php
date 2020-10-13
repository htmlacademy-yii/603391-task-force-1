<?php


namespace frontend\controllers;


use DateTime;
use frontend\models\Category;
use frontend\models\File;
use frontend\models\forms\CompleteTaskForm;
use frontend\models\forms\CreateTaskForm;
use frontend\models\forms\ResponseTaskForm;
use frontend\models\Profile;
use frontend\models\Response;
use frontend\models\Task;
use TaskForce\Actions\CancelAction;
use TaskForce\Actions\CompleteAction;
use TaskForce\Actions\RefuseAction;
use TaskForce\Actions\RespondAction;
use TaskForce\Exception\FileException;
use TaskForce\Exception\TaskForceException;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class TaskController extends SecureController
{
    private const HTTP_STATUS_403 = 403;

    /**
     * @return string
     * @throws HttpException
     * @throws FileException
     */
    public function actionCreate(): string
    {
        $id = Yii::$app->user->getId();
        $role = Profile::findProfileByUserId($id)['role'];


        if ($role !== \TaskForce\Task::ROLE_CUSTOMER) {
            throw new HttpException(self::HTTP_STATUS_403, 'Access denied.');
        }

        $categories = ArrayHelper::map(Category::find()->asArray()->all(), 'id', 'name');
        $createTaskForm = new CreateTaskForm();

        if ($request = Yii::$app->request->post()) {
            $createTaskForm->load($request);
            $createTaskForm->files = UploadedFile::getInstances($createTaskForm, 'files');

            if (!$createTaskForm->validate()) {
                return $this->render('create', compact('createTaskForm', 'categories'));
            }
            $taskID = $createTaskForm->saveData($id);

            if ($taskID) {
                Yii::$app->session->setFlash('success', 'Задача создана');
                $this->redirect('/task/view/' . $taskID);
            }
        }


        return $this->render('create', compact('createTaskForm', 'categories'));
    }

    /**
     *
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     * @throws TaskForceException
     * @throws Exception
     */
    public function actionView(int $id): string
    {
        $responseTaskForm = new ResponseTaskForm();
        $completeTaskForm = new CompleteTaskForm();

        $currentUserId = Yii::$app->user->getId();
        $currentUserRole = Profile::findProfileByUserId($currentUserId)['role'];

        $modelTask = Task::findTaskById($id);
        if (!$modelTask) {
            throw new NotFoundHttpException("Задание с ID $id не найдено");
        }
        $taskOwnerId = $modelTask['customer_id'];
        $taskExecutorId = $modelTask['executor_id'];
        $task = new \TaskForce\Task(
            (int)$modelTask['executor_id'], (int)$modelTask['customer_id'], new DateTime($modelTask['expire']),
            $modelTask['status']
        );

        $availableActions = $task->getAvailableActions($currentUserId);

        if ($currentUserId === (int)$taskOwnerId) {
            $modelsResponse = Response::findResponsesByTaskId($id)->asArray()->all();;
        } else {
            $modelsResponse = Response::findResponsesByTaskId($id)->Andwhere(['r.user_id' => $currentUserId])->asArray(
            )->all();
        }

        $taskAssistUserId = ($currentUserId == $taskOwnerId && $taskExecutorId)
            ? $modelTask['executor_id'] : $modelTask['customer_id'];

        $modelsFiles = File::findFilesByTaskID($id);

        $modelTaskUser = [];
        if ($taskAssistUserId) {
            $modelTaskUser = Profile::findProfileByUserId($taskAssistUserId);
            $modelTaskUser['countTask'] = Task::findCountTasksByUserId($taskAssistUserId);
        }

        return $this->render(
            'view',
            compact(
                'modelTask',
                'modelsFiles',
                'modelsResponse',
                'modelTaskUser',
                'currentUserRole',
                'availableActions',
                'responseTaskForm',
                'completeTaskForm'
            )
        );
    }

    /**
     * Action Response
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     * @throws TaskForceException
     * @throws \Throwable
     */
    public function actionResponse(int $id)
    {
        $task = Task::findOne($id);

        if (!$id) {
            throw new NotFoundHttpException("Task with ID #$id not found.");
        }

        $userId = Yii::$app->user->getId();
        $existResponse = Response::findResponsesByTaskIdUserId($id, $userId);

        if ($existResponse) {
            Yii::$app->session->setFlash('success', "Отклик уже сущестует");
            return $this->redirect(['task/view', 'id' => $id]);
        }

        $role = Profile::findProfileByUserId($id)['role'];
        $isAllow = RespondAction::isAllowed(($id === $userId), $task['status'], $role);

        $responseTaskForm = new ResponseTaskForm();
        if (Yii::$app->request->isPost) {
            $responseTaskForm->load(\Yii::$app->request->post());

            if ($responseTaskForm->validate() && $isAllow) {
                $responseTaskForm->createResponse($id, $userId);
            }
        }

        return $this->redirect(['task/view', 'id' => $id]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionRefuse(int $id)
    {
        $task = Task::findOne($id);
        $taskId = (int)$task->id;

        if (!$taskId) {
            throw new NotFoundHttpException("Task with ID #$id not found.");
        }

        $userId = Yii::$app->user->getId();

        $role = Profile::findProfileByUserId($id)['role'];
        $isAllow = RefuseAction::isAllowed(($taskId === $userId), $task->status, $role);

        if ($isAllow && Yii::$app->request->isPost) {
            $task->status = \TaskForce\Task::STATUS_FAILED;
            $task->save();
            Yii::$app->session->setFlash('success', 'Задача отклонена');
            $this->goHome();
        } else {
            throw new HttpException(403, 'Denied');
        }


        return $this->redirect(['task/view', 'id' => $taskId]);
    }

    /**     * Show task by ID
     *
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionCancel(int $id)
    {
        $task = Task::findOne($id);
        if (!$id) {
            throw new NotFoundHttpException("Task with ID #$id not found.");
        }
        $userId = Yii::$app->user->getId();
        $customerId = $task->customer_id;
        $role = Profile::findProfileByUserId($userId)['role'];
        $isAllow = CancelAction::isAllowed(($customerId === $userId), $task->status, $role);

        if ($isAllow && Yii::$app->request->isPost) {
            $task->status = \TaskForce\Task::ACTION_CANCEL;
            $task->save();
            Yii::$app->session->setFlash('success', 'Задача отклонена');
            $this->goHome();
        } else {
            throw new HttpException(403, 'Denied');
        }

        $this->redirect(['task/view', 'id' => $id]);
    }

    /**
     *
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionComplete(int $id)
    {
        $task = Task::findOne($id);
        if (!$id) {
            throw new NotFoundHttpException("Task with ID #$id not found.");
        }

        $userId = Yii::$app->user->getId();
        $role = Profile::findProfileByUserId($id)['role'];
        $isAllow = CompleteAction::isAllowed(($task->customer_id === $userId), $task->status, $role);

        $completeTaskForm = new CompleteTaskForm();
        if (Yii::$app->request->isPost) {
            $completeTaskForm->load(\Yii::$app->request->post());

            if ($completeTaskForm->validate() && $isAllow) {
                $task->status = \TaskForce\Task::STATUS_COMPLETE;
                $task->save();
            }
        }

        return $this->redirect(['task/view', 'id' => $id]);
    }

}
