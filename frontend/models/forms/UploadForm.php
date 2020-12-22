<?php

namespace frontend\models\forms;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    public ?UploadedFile $file;

    public function rules()
    {
        return [
            ['file', 'file',  'mimeTypes' =>'image/*', 'maxSize' => 1024*1024, 'tooBig' => 'Максимальный размер 1Mb',
                'maxFiles' => 1,
                'skipOnEmpty' => true,]
        ];
    }
}
