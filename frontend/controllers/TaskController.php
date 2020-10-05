<?php


namespace frontend\controllers;


use frontend\models\Category;
use frontend\models\File;
use frontend\models\forms\CreateTaskForm;
use frontend\models\Profile;
use frontend\models\Response;
use frontend\models\Task;
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
        };

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
                $this->redirect('../tasks/view/' . $taskID);
            }
        }


        return $this->render('create', compact('createTaskForm', 'categories'));
    }

    /**
     * Show task by ID
     *
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     * @throws TaskForceException
     */
    public function actionView(int $id): string
    {
        $currentUserId = Yii::$app->user->getId();
        $currentUserRole = Profile::findProfileByUserId($currentUserId)['role'];

        $modelTask = Task::findTaskById($id);
        if (!$modelTask) {
            throw new NotFoundHttpException("Задание с ID $id не найдено");
        }
        $taskOwnerId = $modelTask['customer_id'];
        $taskExecutorId = $modelTask['executor_id'];

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
                'currentUserRole'
            )
        );
    }
}
