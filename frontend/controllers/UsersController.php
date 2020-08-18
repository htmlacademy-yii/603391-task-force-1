<?php


namespace frontend\controllers;

use frontend\models\Opinion;
use frontend\models\Profile;
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
        $models = Profile::findActiveProfiles();

        return $this->render('index', [
            'models' => $models,
        ]);
    }


}
