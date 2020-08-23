<?php


namespace frontend\controllers;

use frontend\models\Task;
use yii\web\Controller;

class TasksController extends Controller
{
    /**
     *
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $models = Task::findNewTask();

        return $this->render('index', [
            'models' => $models || [],
        ]);

    }
}
