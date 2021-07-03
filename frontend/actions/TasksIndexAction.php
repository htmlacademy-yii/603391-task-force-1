<?php

namespace frontend\actions;

use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\TasksFilterForm;
use Yii;
use yii\base\Action;

/**
 * Task list
 */
class TasksIndexAction extends Action
{
    public function run()
    {
        $modelCategoriesFilter = new CategoriesFilterForm();
        $modelCategoriesFilter->init();

        $modelTasksFilter = new TasksFilterForm();
        $getRequest = Yii::$app->request->queryParams;
        if (isset($getRequest['category'])) {
            $modelCategoriesFilter->setOneCategory($getRequest['category']);
        }

        if ($postRequest = yii::$app->request->post()) {
            $modelCategoriesFilter->updateProperties(
                ($postRequest)['CategoriesFilterForm']['categories']
            );
            $dataProvider = $modelTasksFilter->search($postRequest);
        } else {
            $dataProvider = $modelTasksFilter->search($getRequest);
        }

        return $this->controller->render(
            view:'index',
            params: compact(
                'modelTasksFilter',
                'modelCategoriesFilter',
                'dataProvider'
            )
        );
    }
}