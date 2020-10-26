<?php

namespace frontend\controllers;

use frontend\models\City;
use frontend\models\forms\SignupForm;
use TaskForce\Exception\TaskForceException;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class SignupController extends Controller
{
    /**
     * @param  $action
     * @return bool
     */
    public function beforeAction($action)
    {
        if (Yii::$app->user->identity) {
            $this->redirect('tasks/index');
        }

        return true;
    }

    /**
     * @return string|Response
     * @throws TaskForceException
     */
    public function actionIndex()
    {
        $model = new SignupForm();
        if (Yii::$app->request->getIsPost()) {
            $model->load(Yii::$app->request->post());

            if ($model->validate() && $model->register()) {
                Yii::$app->session->setFlash('success', 'Пользователь зарегистрирован.');
                return $this->goHome();
            }
        }

        $cities = City::getList();

        return $this->render('index', compact('model', 'cities'));
    }

}