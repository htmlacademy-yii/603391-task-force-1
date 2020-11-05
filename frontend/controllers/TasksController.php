<?php

namespace frontend\controllers;

use frontend\models\File;
use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\CompleteTaskForm;
use frontend\models\forms\ResponseTaskForm;
use frontend\models\forms\TasksFilterForm;
use frontend\models\Profile;
use frontend\models\Response;
use Exception;
use TaskForce\Exception\TaskForceException;
use TaskForce\Helpers\Declination;
use TaskForce\TaskEntity;
use yii;
use frontend\models\Task;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class TasksController extends SecureController
{
    /**
     * Task list
     *
     * @return string
     * @throws TaskForceException
     */
    public function actionIndex(): string
    {
        $filterRequest = [];
        $modelTasksFilter = new TasksFilterForm();
        $modelCategoriesFilter = new CategoriesFilterForm();
        $modelCategoriesFilter->init();

        if (Yii::$app->request->getIsPost()) {
            $modelTasksFilter->load(Yii::$app->request->post());
            $modelCategoriesFilter->updateProperties(
                (Yii::$app->request->post())['CategoriesFilterForm']['categories']
            );

            $filterRequest = (Yii::$app->request->post());
        }

        if (Yii::$app->request->getIsGet()) {
            $ids = Yii::$app->request->get();
            if (isset($ids['category'])) {
                $modelCategoriesFilter->setOneCategory($ids['category']);
                $filterRequest['CategoriesFilterForm']['categories'] = $modelCategoriesFilter->getCategoriesState();
            }
        }

        $modelsTasks = Task::findNewTask($filterRequest);

        $pagination = new Pagination(
            [
                'totalCount' => $modelsTasks->count(),
                'pageSize' => 5,
                'forcePageParam' => false,
                'pageSizeParam' => false
            ]
        );

        $modelsTasks = $modelsTasks->offset($pagination->offset)->limit($pagination->limit)->all();

        if (isset($modelsTasks)) {
            foreach ($modelsTasks as $key => $element) {
                $modelsTasks[$key]['afterTime'] = Declination::getTimeAfter($element['date_add']);
            }
        }

        return $this->render(
            'index',
            compact('modelsTasks', 'modelTasksFilter', 'modelCategoriesFilter', 'pagination')
        );
    }

    /**
     *
     * @param int $id
     * @return string
     * @throws TaskForceException
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

        $modelsResponse = Response::findResponsesByTask($task->model);

        $ids = ArrayHelper::getColumn($modelsResponse, 'user_id');
        $existsUserResponse = in_array(Yii::$app->user->identity->getId(), $ids);

        $taskAssistUserId = $task->getAssistUserId();

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
                'completeTaskForm',
                'existsUserResponse'
            )
        );
    }


}
