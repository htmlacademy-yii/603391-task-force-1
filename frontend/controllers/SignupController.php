<?php


namespace frontend\controllers;


use frontend\models\City;
use frontend\models\forms\SignupForm;
use frontend\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\widgets\ActiveForm;

class SignupController extends Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return true;
    }


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
        $this->enableCsrfValidation = false;
        return $this->render('index', compact('model', 'cities'));


    }


}
