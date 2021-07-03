<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\TaskList;

class TasksController extends ApiController
{
    public $modelClass = TaskList::class;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        unset(
            $actions['view'],
            $actions['update'],
            $actions['delete'],
            $actions['create']
        );

        return $actions;
    }
}