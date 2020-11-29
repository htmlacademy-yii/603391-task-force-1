<?php

namespace frontend\controllers;

use frontend\models\Task;
use TaskForce\Constant\MyTask;

class MyListController extends SecureController
{
    /**
     * My Task list
     * @param string $filter
     * @return string
     */
    public function actionIndex(string $filter = MyTask::FILTER_COMPLETED): string
    {
        $modelTasks = Task::getTaskByStatus($filter);
        $currentFilter = $filter;
        return $this->render(
            'index', compact('modelTasks','currentFilter')
        );
    }
}