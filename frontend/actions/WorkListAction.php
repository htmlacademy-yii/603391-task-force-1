<?php

namespace frontend\actions;

use frontend\models\Work;
use Yii;
use yii\base\Action;

class WorkListAction extends Action
{
    public function run(): bool|string
    {
        $userId = Yii::$app->user->getId();
        $files = Work::find()->where(['user_id'=>$userId])->all();
        $result = null;
            foreach ($files as $file) {
                    $obj['name'] = $file->generated_name;
                    $obj['id'] = $userId;
                    $result[] = $obj;
                }

        return json_encode($result);
    }
}