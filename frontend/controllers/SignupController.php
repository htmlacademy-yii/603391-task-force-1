<?php


namespace frontend\controllers;


use frontend\models\City;
use frontend\models\forms\SignupForm;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;


class SignupController extends Controller
{
    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        if (Yii::$app->user->identity) {
            $this->redirect('tasks/index');
        };

        $this->enableCsrfValidation = false;
        return true;
    }


    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionIndex()
    {
        $model = new SignupForm();
        if (Yii::$app->request->isPost) {
            $model->load(\Yii::$app->request->post());

            if ($model->validate() && $model->register()) {
                return $this->goHome();
            }
        }

        $cities = ArrayHelper::map(City::find()->asArray()->all(), 'id', 'city');
        return $this->render('index', compact('model', 'cities'));
    }


}
