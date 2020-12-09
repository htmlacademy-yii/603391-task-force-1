<?php

namespace frontend\controllers;

use frontend\models\City;
use frontend\models\forms\SignupForm;
use TaskForce\Exception\TaskForceException;
use Yii;
use yii\base\Action;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

class SignupController extends Controller
{
    use HasTitle;

    public $layout = 'mini';

    /**
     * @param Action $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        $this->getTitle();
        return parent::beforeAction($action);
    }

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
     * @return string|Response
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
        $cities = City::getList();

        return $this->render('index', compact('model', 'cities'));
    }
}
