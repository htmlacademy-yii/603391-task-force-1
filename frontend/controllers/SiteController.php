<?php

namespace frontend\controllers;

/**
 * Site controller
 */
class SiteController extends SecureController
{

    /**
     * @return array|string[][]
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ]
        ];
    }

}


