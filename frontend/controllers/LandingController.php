<?php

namespace frontend\controllers;

use frontend\models\forms\LoginForm;
use frontend\models\forms\SignupForm;
use Yii;
use yii\web\Controller;



/**
 * Site controller
 */
class LandingController extends Controller
{
    public $loginForm;

    public function actionIndex(): string
    {
        $this->layout = 'landing';
        $this->loginForm = new LoginForm();
        return $this->render('index', compact('loginForm'));
    }

    public function actionLogin()
    {
        $this->layout = 'landing';
        $loginForm = new LoginForm();
        if (\Yii::$app->request->getIsPost()) {
            $loginForm->load(Yii::$app->request->post());

            if ($loginForm->validate()) {
                $user = $loginForm->getUser();
                Yii::$app->user->login($user);
                return $this->goHome();
            }
        }
    }

}


