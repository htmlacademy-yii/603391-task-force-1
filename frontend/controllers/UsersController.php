<?php


namespace frontend\controllers;

use frontend\models\Opinion;
use frontend\models\Task;
use TaskForce\Helpers\Utils;
use yii\db\Query;
use yii\web\Controller;

class UsersController extends Controller
{
    /**
     *
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $query = new Query();
        $query->select(['p.*', 'u.name', 'u.date_login'])->from('profile p')
            ->join('LEFT JOIN', 'user as u', 'p.user_id = u.id')
            ->where("p.role = 'executor'")
            ->orderBy(['u.date_add' => SORT_DESC]);

        $model = $query->all();

        foreach ($model as $key => $element) {
            $query = new Query();
            $query->select('c.name')->from('specialisation s')
                ->join('LEFT JOIN', 'category as c', 's.category_id = c.id')
                ->where("profile_id = " . $element['id']);
            $model[$key]['categories'] = $query->all();


            $model[$key]['countTasks'] = Task::find()
                ->where(["executor_id" => $element['id']])
                ->count();

            $model[$key]['countReplies'] = Opinion::find()
                ->where(['executor_id' => $element['id']])
                ->count();

            $model[$key]['afterTime'] = Utils::timeAfter($element['date_login']);
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }


}
