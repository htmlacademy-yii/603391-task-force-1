<?php

namespace frontend\controllers;

use frontend\models\City;
use frontend\models\forms\SignupForm;
use TaskForce\Exception\TaskForceException;
use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\Response;


class SignupController extends Controller
{
    /**
     * @param Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        if (Yii::$app->user->identity) {
            $this->redirect('tasks/index');
        }

        $this->enableCsrfValidation = false;

        return true;
    }


    /**
     * @return string|Response
     * @throws Exception
     * @throws TaskForceException
     */
    public function actionIndex()
    {
        $model = new SignupForm();
        if (Yii::$app->request->getIsPost()) {
            $model->load(Yii::$app->request->post());

            if ($model->validate() && $model->register()) {
                return $this->goHome();
            }
        }

        $cities = City::getAll();

        return $this->render('index', compact('model', 'cities'));
    }


}
