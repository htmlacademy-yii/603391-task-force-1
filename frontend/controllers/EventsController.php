<?php

namespace frontend\controllers;

use frontend\models\Event;
use Yii;

class EventsController extends SecureController
{
    /**
     * @return void
     */
    public function actionClear(): void
    {
        if (!$userId = Yii::$app->user->getId()) {
            return;
        }
        Event::updateAll(['viewed' => 1], "user_id=$userId");
    }
}