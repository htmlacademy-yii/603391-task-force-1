<?php

namespace frontend\controllers;

use frontend\models\forms\LoginForm;
use Yii;
use yii\web\Controller;


/**
 * Site controller
 */
class LandingController extends Controller
{
    public $loginForm;

    public function actionIndex()
    {
        if (\Yii::$app->user->getId()) {
            $this->redirect(['tasks/index']);
        }

        $this->layout = 'landing';
        $this->loginForm = new LoginForm();
        return $this->render('index', compact('loginForm'));
    }


    public function actionLogin()
    {
        $this->loginForm = new LoginForm();
        if (\Yii::$app->request->getIsPost()) {
            $this->loginForm->load(Yii::$app->request->post());
            if ($this->loginForm->validate()) {
                $user = $this->loginForm->getUser();
                \Yii::$app->user->login($user);
                $this->redirect(['tasks/index']);
            } else {
                Yii::$app->session->setFlash('login-error', "Не верный логин или пароль.");
            }
        }

        $this->redirect(['landing/index']);
    }

}


