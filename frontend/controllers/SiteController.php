<?php

namespace frontend\controllers;

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


