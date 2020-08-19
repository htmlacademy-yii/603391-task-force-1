<?php


namespace frontend\controllers;

use frontend\models\Profile;
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
        $models = Profile::findNewExecutors();

        return $this->render('index', [
            'models' => $models,
        ]);
    }


}
