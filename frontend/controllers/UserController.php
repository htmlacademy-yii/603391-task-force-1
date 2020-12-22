<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class UserController extends Controller
{
     /**
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
