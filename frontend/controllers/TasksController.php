<?php


namespace frontend\controllers;


use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\TasksFilterForm;
use TaskForce\Exception\TaskForceException;
use yii;
use frontend\models\Task;
use yii\web\Controller;

class TasksController extends Controller
{
    /**
     * Список заданий в статусе 'Новый', без привязки к адресу
     *
     * @return mixed
     * @throws TaskForceException
     */
    public function actionIndex()
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

        return $this->render('index', [
            'modelsTasks' => $modelsTasks ?? [],
            'modelTasksFilter' => $modelTasksFilter,
            'modelCategoriesFilter' => $modelCategoriesFilter,
        ]);

    }

}
