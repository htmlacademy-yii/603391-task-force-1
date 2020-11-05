<?php

namespace frontend\controllers;

use frontend\models\forms\LoginForm;
use frontend\models\Task;
use TaskForce\Exception\TaskForceException;
use TaskForce\Helpers\Declination;
use Yii;
use yii\web\Controller;

/**
 * Landing controller
 */
class LandingController extends Controller
{
    public LoginForm $loginForm;

    /**
     * @return string
     * @throws TaskForceException
     */
    public function actionIndex()
    {
        if (Yii::$app->user->getId()) {
            $this->redirect(['tasks/index']);
        }

        $this->layout = 'landing';
        $this->loginForm = new LoginForm();

        $modelsTasks = Task::findNewTask()->limit(4)->all();
        if (isset($modelsTasks)) {
            foreach ($modelsTasks as $key => $element) {
                $modelsTasks[$key]['afterTime'] = Declination::getTimeAfter($element['date_add']);
            }
        }

        return $this->render('index', compact('loginForm', 'modelsTasks'));
    }


    /**
     * Action Login
     */
    public function actionLogin(): void
    {
        $this->loginForm = new LoginForm();
        if (Yii::$app->request->getIsPost()) {
            $this->loginForm->load(Yii::$app->request->post());
            if ($this->loginForm->validate()) {
                $user = $this->loginForm->getUser();
                Yii::$app->user->login($user);
                $this->redirect(['tasks/index']);
            } else {
                Yii::$app->session->setFlash('login-error', "Не верный логин или пароль.");
            }
        }

        $this->redirect(['landing/index']);
    }

}


