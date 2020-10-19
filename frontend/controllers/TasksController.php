<?php

namespace frontend\controllers;

use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\TasksFilterForm;
use TaskForce\Exception\TaskForceException;
use TaskForce\Helpers\Declination;
use yii;
use frontend\models\Task;
use yii\data\Pagination;

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
            $modelCategoriesFilter->updateProperties((Yii::$app->request->post())['CategoriesFilterForm']['categories']);

            $filterRequest = (Yii::$app->request->post());
        }

        if (Yii::$app->request->getIsGet()) {
            $ids = Yii::$app->request->get();
            if   (isset($ids['category'])) {
                $modelCategoriesFilter->setOneCategory($ids['category']);
                $filterRequest['CategoriesFilterForm']['categories'] = $modelCategoriesFilter->getCategoriesState();
            }
        }

        $modelsTasks = Task::findNewTask($filterRequest);

        $pagination = new Pagination(['totalCount' => $modelsTasks->count(), 'pageSize' => 5, 'forcePageParam' => false,
            'pageSizeParam' => false]);

        $modelsTasks = $modelsTasks->offset($pagination->offset)->limit($pagination->limit)->all();

        if (isset($modelsTasks)) {
            foreach ($modelsTasks as $key => $element) {
                $modelsTasks[$key]['afterTime'] = Declination::getTimeAfter($element['date_add']);
            }
        }

        return $this->render('index', compact('modelsTasks', 'modelTasksFilter', 'modelCategoriesFilter', 'pagination'));
    }



}
