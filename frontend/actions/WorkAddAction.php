<?php

namespace frontend\actions;

use frontend\models\forms\UploadForm;
use frontend\models\Work;
use Yii;
use yii\base\Action;
use yii\helpers\Json;
use yii\web\UploadedFile;

class WorkAddAction extends Action
{
    /**
     * @return string
     * @throws \TaskForce\Exception\FileException
     * @throws \yii\base\Exception
     */
    public function run()
    {
        $response = [];
        $FORM_PARAM = 'file';
        $model = new UploadForm();
        if (Yii::$app->request->post()) {
            $model->file = UploadedFile::getInstanceByName(name: $FORM_PARAM);
            if ($model->validate()) {
                $fileName = Work::saveFile(file: $model->file);
                $response = [
                    'filename' => $fileName,
                ];
            }
        }

        return Json::encode($response);
    }
}