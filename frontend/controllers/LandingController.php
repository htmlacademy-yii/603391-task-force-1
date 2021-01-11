<?php

namespace frontend\controllers;

use frontend\models\forms\LoginForm;
use frontend\models\Task;
use TaskForce\Helpers\Declination;
use TaskForce\Exception\TaskForceException;
use Yii;
use yii\web\Controller;

class LandingController extends Controller
{
    public $layout = 'landing';

    /**
     * @return string
     * @throws TaskForceException
     */
    public function actionIndex()
    {
        if (Yii::$app->user->getId()) {
            $this->redirect(['tasks/index']);
        }
        $modelsTasks = Task::findNewTask()->limit(4)->all();
        if (isset($modelsTasks)) {
            foreach ($modelsTasks as $key => $element) {
                $modelsTasks[$key]['afterTime'] = Declination::getTimeAfter($element['date_add']);
            }
        }

        return $this->render('index', compact( 'modelsTasks'));
    }

    /**
     * Action Login
     */
    public function actionLogin(): void
    {
        $loginForm = new LoginForm();
        if ($post = Yii::$app->request->post()) {
            $loginForm->load($post);
            if ($loginForm->validate()) {
                $user = $loginForm->getUser();
                Yii::$app->user->login($user);
                $this->redirect(['tasks/index']);
            } else {
                Yii::$app->session->setFlash('error', "Не верный логин или пароль.");
            }
        }
        $this->redirect(['landing/index']);
    }
}


