<?php


namespace frontend\controllers;


use frontend\models\File;
use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\TasksFilterForm;
use frontend\models\Profile;
use frontend\models\Response;
use TaskForce\Exception\TaskForceException;
use TaskForce\Helpers\Utils;
use yii;
use frontend\models\Task;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TasksController extends Controller
{
    /**
     * Список заданий в статусе 'Новый', без привязки к адресу
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
            $modelCategoriesFilter->updateProperties((Yii::$app->request->post())['CategoriesFilterForm']['categories']);

            $filterRequest = (Yii::$app->request->post());
        }

        $modelsTasks = Task::findNewTask($filterRequest);

        $pagination = new Pagination(['totalCount' => $modelsTasks->count(), 'pageSize' => 5, 'forcePageParam' => false,
            'pageSizeParam' => false]);

        $modelsTasks = $modelsTasks->offset($pagination->offset)->limit($pagination->limit)->all();

        if (isset($modelsTasks)) {
            foreach ($modelsTasks as $key => $element) {
                $modelsTasks[$key]['afterTime'] = Utils::getTimeAfter($element['date_add']);
            }
        }

        return $this->render('index', compact('modelsTasks', 'modelTasksFilter', 'modelCategoriesFilter', 'pagination'));
    }

    /**
     * Просмотр задания c id
     *
     * @param int $id
     * @return string
     * @throws TaskForceException
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        $modelTask = Task::findTaskById($id);

        if (!$modelTask) {
            throw new NotFoundHttpException("Задание с ID $id не найдено");
        }

        $modelsResponse = Response::findResponsesByTaskId($id);
        $currentUser = 'customer'; // изменить после создания авторизации
        $userId = ($currentUser == 'customer') ? $modelTask['executor_id'] : $modelTask['customer_id'];
        $modelsFiles = File::findFilesByTaskID($id);

        $modelTaskUser = [];
        if ($modelTask['executor_id']) {
            $modelTaskUser = Profile::findProfileByUserId($userId);
            $modelTaskUser['countTask'] = Task::findCountTasksByUserId($userId);
        }

        return $this->render('view', compact('modelTask', 'modelsFiles', 'modelsResponse',
            'modelTaskUser', 'currentUser'));

    }

}
