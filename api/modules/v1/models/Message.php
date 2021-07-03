<?php

namespace api\modules\v1\models;

use frontend\models\Chat;
use Yii;

class Message extends Chat
{
    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'message' => 'message',
            'published_at' => function () {
                return Yii::$app->formatter->asRelativeTime($this->created_at);
            },
            'is_mine' => function () {
                return Yii::$app->user->getId() === $this->user_id;
            }
        ];
    }
}