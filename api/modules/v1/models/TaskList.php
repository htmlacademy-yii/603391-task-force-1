<?php

namespace api\modules\v1\models;

use frontend\models\Event;
use frontend\models\Task;
use frontend\models\User;
use TaskForce\Constant\NotificationID;
use Yii;

class TaskList extends Task
{
    public function fields()
    {
        return [
            'title' => 'name',
            'published_at' => 'date_add',
            'new_messages' => function () {

                return Event::find()->where(
                    [
                        'user_id' => Yii::$app->user->identity->getId(),
                        'task_id' => $this->id,
                        'viewed' => false,
                        'notification_id' => NotificationID::NEW_MESSAGE
                    ]
                )
                    ->count();
            },
            'author_name' => function () {

                return User::findOne($this->customer_id)->name;
            },
            'id'
        ];
    }
}