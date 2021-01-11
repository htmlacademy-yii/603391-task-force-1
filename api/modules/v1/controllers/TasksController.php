<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\TaskList;
use yii\rest\ActiveController;

class TasksController extends ActiveController
{
    public $modelClass = TaskList::class;

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