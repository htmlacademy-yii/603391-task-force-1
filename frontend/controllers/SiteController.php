<?php

namespace frontend\controllers;

use frontend\models\File;
use TaskForce\Exception\FileException;
use Yii;

/**
 * Site controller
 */
class SiteController extends SecureController
{
    /**
     * @return array|string[][]
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ]
        ];
    }

    /**
     * @param int $id
     * @throws FileException
     */
    public function actionFile(int $id)
    {
        File::forceDownloadTaskFile($id);
    }

    /**
     * Set City id to session
     *
     * @param int $cityId
     */
    public function actionCity(int $cityId):void
    {
        $session = Yii::$app->session;
        $session['current_city_id'] = $cityId;
    }
}