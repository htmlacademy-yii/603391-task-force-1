<?php

namespace frontend\controllers;

use frontend\models\File;

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

    /**
     * @param int $id
     */
    public function actionFile(int $id)
    {
        File::forceDownloadTaskFile($id);
    }
}


