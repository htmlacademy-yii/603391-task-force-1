<?php

namespace api\modules\v1\controllers;

use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\Cors;
use yii\rest\ActiveController;

class ApiController extends ActiveController
{


    function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['create', 'index'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Access-Control-Request-Method' => ['GET, POST, OPTIONS'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 3600,
            ]
        ];

        return $behaviors;
    }


}