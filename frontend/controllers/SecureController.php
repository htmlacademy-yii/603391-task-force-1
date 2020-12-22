<?php

namespace frontend\controllers;

use yii\base\Action;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

abstract class SecureController extends Controller
{
   public $layout = 'main';

   use HasTitle;

    /**
     * @param Action $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
       $this->getTitle();
       return parent::beforeAction($action);
   }

    /**
     * @return array|array[]
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ]
            ],
        ];
    }
}
