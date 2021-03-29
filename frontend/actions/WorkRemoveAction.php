<?php

namespace frontend\actions;

use Yii;
use yii\base\Action;
use yii\helpers\Url;

class WorkRemoveAction extends Action
{
    public function run(string $filename): int
    {
        return (int)unlink(Url::to('uploads/works') . '/' . Yii::$app->user->getId() .'/'. $filename);
    }
}