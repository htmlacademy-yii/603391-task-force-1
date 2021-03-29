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
        Event::updateAll(['viewed' => 1],  ['=', 'user_id', Yii::$app->user->getId()]);
    }
}