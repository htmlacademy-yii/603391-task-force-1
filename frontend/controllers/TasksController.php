<?php


namespace frontend\controllers;

use frontend\models\Task;
use frontend\models\TaskQuery;
use TaskForce\Helpers\Utils;
use Yii;
use yii\db\Query;
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
            'models' => $models,
        ]);

    }
}
