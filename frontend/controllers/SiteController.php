<?php

namespace frontend\controllers;

use yii\web\Controller;


/**
 * Site controller
 */
class SiteController extends SecureController
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ]
        ];
    }

}


