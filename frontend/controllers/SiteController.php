<?php

namespace frontend\controllers;

use frontend\components\AuthHandler;
use frontend\models\File;
use TaskForce\Exception\FileException;
use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends  Controller
{
    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    /**
     * @param $client
     */
    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
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