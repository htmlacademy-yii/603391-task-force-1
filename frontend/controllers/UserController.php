<?php


namespace frontend\controllers;

use Yii;
use yii\web\Controller;


class UserController extends Controller
{
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

}
