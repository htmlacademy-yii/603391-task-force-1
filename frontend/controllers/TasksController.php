<?php

namespace frontend\controllers;

use frontend\models\File;
use frontend\models\forms\CompleteTaskForm;
use frontend\models\forms\ResponseTaskForm;
use frontend\models\Profile;
use frontend\models\Response;
use Exception;
use frontend\models\User;
use TaskForce\Constant\UserRole;
use TaskForce\TaskEntity;
use yii;
use frontend\models\Task;
use yii\helpers\ArrayHelper;

class TasksController extends SecureController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => 'frontend\actions\TasksIndexAction',
            ],
        ];
    }

    /**
     * View task
     * @param int $id
     * @return string
     * @throws Exception
     */
    public function actionView(int $id): string
    {
        $responseTaskForm = new ResponseTaskForm();
        $completeTaskForm = new CompleteTaskForm();
        $currentUserRole = Yii::$app->user->identity->role;
        $modelTask = Task::findTaskTitleInfoByID($id);
        $task = new TaskEntity($id);
        $availableActions = $task->getAvailableActions();
        $modelsResponse = Response::findByTask($task->model);
        $ids = ArrayHelper::getColumn($modelsResponse, 'user_id');
        $existsUserResponse = in_array(Yii::$app->user->identity->getId(), $ids);
        $modelsFiles = File::findByTaskID($id);
        $taskAssistUserId = $task->getAssistUserId();
        $assistUserModel = [];
        if ($taskAssistUserId) {
            $assistUserModel = Profile::findByUserId($taskAssistUserId);
            $assistUserModel['countTask'] = Task::findCountByUserId($taskAssistUserId);
            $assistUserModel['isExecutor'] = (User::findOne($taskAssistUserId)->role === UserRole::EXECUTOR);
        }

        return $this->render('view', compact('modelTask', 'modelsFiles', 'modelsResponse',
                                             'assistUserModel', 'currentUserRole', 'availableActions',
                                             'responseTaskForm', 'completeTaskForm', 'existsUserResponse'));
    }
}
