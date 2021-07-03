<?php

namespace TaskForce\widgets;

use frontend\models\Event;
use Yii;
use yii\base\Widget;

class BulbWidget extends Widget
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        parent::run();

        if ($isLoggedUser = Yii::$app->user->identity) {
            $eventsCount = Event::findEventsForUser($isLoggedUser->id);
        }

        return $this->render('@widgets/bulb/view', compact('eventsCount'));
    }
}