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


        $query = new Query();
        $query->select(['t.*', 'c.name as cat_name', 'c.icon as icon'])->from('task t')
            ->join('LEFT JOIN', 'category as c', 't.category_id = c.id')
            ->where('t.status_id = 1') // 1 - Status New
            ->orderBy(['date_add' => SORT_DESC])
            ->limit(5);

        $model = $query->all();
        foreach ($model as $key => $element) {
            $model[$key]['afterTime'] = Utils::timeAfter($element['date_add']);

        }
        return $this->render('index', [
            'model' => $model,
        ]);

    }
}
