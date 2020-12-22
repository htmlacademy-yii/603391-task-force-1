<?php

namespace frontend\actions;

use frontend\models\forms\CategoriesFilterForm;
use frontend\models\forms\TasksFilterForm;
use frontend\models\Task;
use TaskForce\Exception\TaskForceException;
use TaskForce\Helpers\Declination;
use Yii;
use yii\base\Action;
use yii\data\Pagination;

/**
 * Task list
 */
class TasksIndexAction extends Action
{
    /**
     * @return string
     * @throws TaskForceException
     */
    public function run()
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

        return $this->controller->render(
            'index',
            compact('modelsTasks', 'modelTasksFilter', 'modelCategoriesFilter', 'pagination')
        );
    }
}