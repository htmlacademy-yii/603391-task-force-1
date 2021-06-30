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
        $request = Yii::$app->request->get();
        if (isset($request['category'])) {
            $modelCategoriesFilter->setOneCategory($request['category']);
        }
        $postRequest = yii::$app->request->post();
        if ($postRequest) {
            $modelCategoriesFilter->updateProperties(
                ($postRequest)['CategoriesFilterForm']['categories']
            );
            $dataProvider = $modelTasksFilter->search($postRequest);
        } else {
            $dataProvider = $modelTasksFilter->search($request);
        }

        return $this->controller->render(
            'index',
            compact(
                'modelTasksFilter',
                'modelCategoriesFilter',
                'dataProvider'
            )
        );
    }
}