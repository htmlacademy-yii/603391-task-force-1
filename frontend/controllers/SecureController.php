<?php

namespace frontend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

abstract class SecureController extends Controller
{
    public $layout = 'main';

   use HasTitle;

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
