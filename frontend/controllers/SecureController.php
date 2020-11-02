<?php

namespace frontend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

abstract class SecureController extends Controller
{
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
