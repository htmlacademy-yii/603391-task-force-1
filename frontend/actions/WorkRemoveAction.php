<?php

namespace frontend\actions;

use frontend\models\Work;
use Yii;
use yii\base\Action;
use yii\helpers\Url;

class WorkRemoveAction extends Action
{

    /**
     * @param string $filename
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function run(string $filename)
    {
        Work::findOne(
            [
                'user_id' => Yii::$app->user->getId(),
                'generated_name' => $filename
            ]
        )->delete();

        return (int)unlink(Url::to('uploads/works') . '/' . Yii::$app->user->getId() . '/' . $filename);
    }
}
