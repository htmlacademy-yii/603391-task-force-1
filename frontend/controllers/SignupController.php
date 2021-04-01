<?php

namespace frontend\controllers;

use frontend\models\City;
use frontend\models\forms\SignupForm;
use TaskForce\Exception\TaskForceException;
use TaskForce\Helpers\UserData;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class SignupController extends Controller
{
    use HasTitle;

    public $layout = 'mini';

    /**
     * @return array|array[]
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?']
                    ],
                ]
            ],
        ];
    }

    /**
     * @throws TaskForceException
     */
    public function actionIndex(): Response|string
    {
        $model = new SignupForm();
        if (Yii::$app->request->getIsPost()) {
            $model->load(Yii::$app->request->post());

            if ($model->validate() && $model->register()) {
                Yii::$app->session->setFlash('success', 'Регистрация завершина.');
                return $this->goHome();
            }
        }
        $cities = City::getList();
        $userCity = UserData::getCityByIp(Yii::$app->getRequest()->getUserIP());
        $userCityKey = array_search($userCity, $cities);

        return $this->render('index', compact('model', 'cities', 'userCityKey'));
    }
}
